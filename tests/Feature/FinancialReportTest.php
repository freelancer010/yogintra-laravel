<?php

namespace Tests\Feature;

use App\Http\Middleware\OperationMiddleware;
use App\Services\FinancialReport;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        foreach (['leads', 'events', 'yoga'] as $table) {
            Schema::create($table, function (Blueprint $table) {
                $table->increments('id');
                foreach (['name', 'client_name', 'class_type', 'payment_type', 'totalPayDate', 'trainerPayDate', 'created_date', 'created_by'] as $column) $table->string($column)->nullable();
                foreach (['full_payment', 'totalPayAmount', 'payTotrainer'] as $column) $table->decimal($column, 14, 2)->default(0);
                $table->integer('status')->default(3);
                $table->integer('read_status')->default(0);
                $table->integer('trainer_id')->nullable();
            });
        }
        Schema::create('expense', function (Blueprint $table) {
            $table->increments('id'); $table->string('payee')->nullable();
            $table->decimal('expenseAmount', 14, 2); $table->string('created_date')->nullable();
        });
        Schema::create('trainer', function (Blueprint $table) {
            $table->increments('id'); $table->string('name')->nullable();
            $table->integer('is_trainer')->default(1); $table->integer('status_trainer')->default(1); $table->integer('read_status')->default(0);
        });
        Schema::create('paymentdata', function (Blueprint $table) {
            $table->increments('id'); $table->integer('leadId'); $table->string('type');
            $table->integer('status')->default(1); $table->decimal('amount', 14, 2); $table->string('created_date')->nullable();
        });
        Schema::create('package_renew_detail', function (Blueprint $table) {
            $table->increments('id'); $table->integer('lead_id'); $table->string('type');
            $table->decimal('renew_amount', 14, 2); $table->string('created_date')->nullable();
        });
    }

    private function report(?string $start = null, ?string $end = null, string $class = 'all'): array
    {
        return app(FinancialReport::class)->build($start, $end, $class);
    }

    public function test_expiry_preserves_historical_receipts_and_trainer_payments(): void
    {
        DB::table('leads')->insert(['full_payment'=>1000, 'payTotrainer'=>400, 'status'=>3]);
        $before=$this->report();
        DB::table('leads')->update(['status'=>5]);
        $after=$this->report();
        $this->assertEquals(1000, $after['totalCredit']);
        $this->assertEquals(400, $after['totalDebit']);
        $this->assertSame($before, $after);
    }

    public function test_all_sources_decimals_and_negative_category_balances_reconcile(): void
    {
        DB::table('leads')->insert(['class_type'=>'Home Visit Yoga','full_payment'=>100.99,'payTotrainer'=>150.01]);
        DB::table('leads')->insert(['class_type'=>'Home Visit Yoga','full_payment'=>50.50,'payTotrainer'=>10.25,'status'=>5]);
        DB::table('yoga')->insert(['totalPayAmount'=>25.25,'status'=>5]);
        DB::table('events')->insert(['class_type'=>'','totalPayAmount'=>20.20]);
        DB::table('expense')->insert(['expenseAmount'=>12.34,'created_date'=>'2026-11-17']);
        DB::table('leads')->insert(['full_payment'=>999,'status'=>6]);
        DB::table('leads')->insert(['full_payment'=>999,'status'=>0]);
        DB::table('yoga')->insert(['totalPayAmount'=>999,'status'=>0]);
        $result=$this->report();
        $this->assertEquals(196.94,$result['totalCredit']);
        $this->assertEquals(172.60,$result['totalDebit']);
        $this->assertEquals(24.34,$result['netAmount']);
        $rows=collect($result['summary'])->keyBy('class_type');
        $this->assertEquals(-8.77,$rows['Home Visit Yoga']['net_amount']);
        $this->assertEquals(20.20,$rows['Uncategorized']['full_payment']);
        $this->assertEquals(25.25,$rows['Yoga Center']['full_payment']);
        $this->assertEquals($result['totalCredit'],array_sum(array_column($result['data'],'full_payment')));
        $this->assertEquals($result['totalDebit'],array_sum(array_column($result['summary'],'payTotrainer')));
    }

    public function test_period_uses_individual_payment_and_debit_dates_including_end_day(): void
    {
        DB::table('leads')->insert(['id'=>1,'payment_type'=>'Partition Payment','full_payment'=>300,'payTotrainer'=>30,'trainerPayDate'=>'2026-11-17 22:00:00','created_date'=>'2025-01-01']);
        DB::table('paymentdata')->insert([
            ['leadId'=>1,'type'=>'lead','amount'=>100,'created_date'=>'2026-10-01'],
            ['leadId'=>1,'type'=>'lead','amount'=>200,'created_date'=>'2026-11-17 23:59:59'],
        ]);
        DB::table('expense')->insert(['expenseAmount'=>12.34,'created_date'=>'2026-11-17 23:59:59']);
        $result=$this->report('2026-11-17','2026-11-17');
        $this->assertEquals(200,$result['totalCredit']);
        $this->assertEquals(42.34,$result['totalDebit']);
        $this->assertSame(0,$result['undatedCount']);
    }

    public function test_undated_receipts_are_in_all_time_totals_and_flagged_outside_periods(): void
    {
        DB::table('leads')->insert(['full_payment'=>100,'created_date'=>'2026-11-17']);
        $this->assertEquals(100,$this->report()['totalCredit']);
        $result=$this->report('2026-11-01','2026-11-30');
        $this->assertEquals(0,$result['totalCredit']);
        $this->assertEquals(100,$result['undatedCredit']);
        $this->assertSame(1,$result['undatedCount']);
    }

    public function test_renewal_is_not_double_counted_or_assigned_to_original_record_date(): void
    {
        DB::table('leads')->insert(['id'=>1,'full_payment'=>350,'totalPayDate'=>'2026-11-17','created_date'=>'2025-01-01']);
        DB::table('package_renew_detail')->insert(['lead_id'=>1,'type'=>'leads','renew_amount'=>50,'created_date'=>'2026-11-17']);
        $this->assertEquals(350,$this->report()['totalCredit']);
        $result=$this->report('2026-11-01','2026-11-30');
        $this->assertEquals(50,$result['totalCredit']);
        $this->assertEquals(300,$result['undatedCredit']);
    }

    public function test_inconsistent_installments_do_not_inflate_stored_receipts(): void
    {
        DB::table('leads')->insert(['id'=>1,'full_payment'=>100,'payment_type'=>'Partition Payment']);
        DB::table('paymentdata')->insert(['leadId'=>1,'type'=>'lead','amount'=>200,'created_date'=>'2026-11-17']);
        $result=$this->report();
        $this->assertEquals(100,$result['totalCredit']);
        $this->assertSame('Payment details require reconciliation',$result['data'][0]['note']);
        $this->assertEquals(0,$this->report('2026-11-01','2026-11-30')['totalCredit']);
    }

    public function test_dashboard_summary_and_ledger_return_the_same_totals(): void
    {
        DB::table('leads')->insert(['full_payment'=>100.99,'payTotrainer'=>50.01,'status'=>5]);
        DB::table('yoga')->insert(['totalPayAmount'=>25.25,'status'=>1]);
        $this->withoutMiddleware(OperationMiddleware::class)->withSession(['admin_role_id'=>1]);
        $dashboard=$this->getJson('/counts')->assertOk()->json();
        $summary=$this->postJson('/summary',['class_type'=>'all'])->assertOk()->json();
        $ledger=$this->postJson('/ledger',['class_type'=>'all'])->assertOk()->json();
        foreach (['totalCredit','totalDebit'] as $key) {
            $this->assertEquals($dashboard[$key],$summary[$key]);
            $this->assertEquals($summary[$key],$ledger[$key]);
        }
        $this->postJson('/summary',['startDate'=>'2026-11-20','endDate'=>'2026-11-01'])->assertUnprocessable();
        $this->postJson('/ledger',['class_type'=>"' OR 1=1 --"])->assertOk()->assertJsonPath('totalCredit',0);
    }

    public function test_empty_report_has_zero_totals(): void
    {
        $result=$this->report();
        $this->assertEquals(0,$result['totalCredit']);
        $this->assertEquals(0,$result['totalDebit']);
        $this->assertSame([],$result['summary']);
    }
}
