<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** One source for dashboard totals, the accounts summary and ledger. */
class FinancialReport
{
    private function cents($amount): int
    {
        return is_numeric($amount) ? (int) round((float) $amount * 100) : 0;
    }

    private function date($value): ?string
    {
        $value = str_replace('T', ' ', trim((string) $value));
        return preg_match('/^\d{4}-\d{2}-\d{2}/', $value) && substr($value, 0, 10) !== '0000-00-00'
            ? $value : null;
    }

    public function build(?string $start = null, ?string $end = null, string $class = 'all'): array
    {
        $entries = collect();
        $payments = DB::table('paymentdata')->where('status', 1)->get()->groupBy(fn ($row) => $row->type . ':' . $row->leadId);
        $renewals = DB::table('package_renew_detail')->get()->groupBy(fn ($row) => $row->type . ':' . $row->lead_id);
        $trainers = DB::table('trainer')->pluck('name', 'id');
        $add = function ($name, $category, int $credit, int $debit, $date, $trainer = '', $note = '') use ($entries) {
            if ($credit === 0 && $debit === 0) return;
            $entries->push([
                'name' => $name, 'class_type' => $category,
                'credit_cents' => $credit, 'debit_cents' => $debit,
                'created_date' => $this->date($date),
                'trainerPayDate' => $debit !== 0 ? $this->date($date) : null,
                'trainerName' => $trainer, 'note' => $note,
            ]);
        };

        foreach (['leads' => 'lead', 'events' => 'event', 'yoga' => 'yoga'] as $table => $type) {
            $records = DB::table($table);
            // Exclude explicitly deleted/voided records and leads transferred to yoga.
            // Renewal, expired and other live statuses retain their recorded amounts.
            if ($table === 'leads') $records->whereNotIn('status', [0, 6]);
            if ($table === 'yoga') $records->where(fn ($query) => $query->whereNull('status')->orWhere('status', '!=', 0));
            foreach ($records->get() as $record) {
                $category = $table === 'yoga' ? 'Yoga Center' : trim((string) ($record->class_type ?? ''));
                if ($category === 'Client Yoga Center') $category = 'Yoga Center';
                $category = $category !== '' ? $category : 'Uncategorized';
                $name = $record->name ?? $record->client_name ?? '';
                $total = $this->cents($table === 'leads' ? ($record->full_payment ?? 0) : ($record->totalPayAmount ?? 0));
                $parts = ($record->payment_type ?? '') === 'Partition Payment'
                    ? $payments->get($type . ':' . $record->id, collect()) : collect();
                $renewal = $renewals->get($table . ':' . $record->id, collect())->sortByDesc('created_date')->first();
                $renewalAmount = $renewal ? $this->cents($renewal->renew_amount) : 0;
                $partTotal = $parts->sum(fn ($part) => $this->cents($part->amount));

                // Never add installment/renewal amounts on top of the cumulative total.
                if ($total < 0 || $partTotal + $renewalAmount > $total || $renewalAmount < 0) {
                    $add($name, $category, $total, 0, null, '', 'Payment details require reconciliation');
                } else {
                    foreach ($parts as $part) $add($name, $category, $this->cents($part->amount), 0, $part->created_date);
                    if ($renewalAmount !== 0) $add($name, $category, $renewalAmount, 0, $renewal->created_date, '', 'Latest recorded renewal');
                    $remaining = $total - $partTotal - $renewalAmount;
                    // Renewals overwrite totalPayDate. Older residual receipts cannot
                    // safely be attributed to that date or the customer's creation date.
                    $date = $renewal || $parts->isNotEmpty() ? null : ($record->totalPayDate ?? null);
                    $add($name, $category, $remaining, 0, $date, '', $date ? '' : 'Payment date unavailable');
                }
                if ($table === 'leads') {
                    $add($name, $category, 0, $this->cents($record->payTotrainer ?? 0), $record->trainerPayDate ?? null,
                        $trainers->get($record->trainer_id ?? null, ''), 'Recorded trainer payment');
                }
            }
        }
        foreach (DB::table('expense')->get() as $expense) {
            $add($expense->payee ?? '', 'Expense', 0, $this->cents($expense->expenseAmount), $expense->created_date, '', 'Office expense');
        }

        $entries = $entries->filter(fn ($row) => $class === 'all' || $row['class_type'] === $class);
        $undated = $entries->whereNull('created_date');
        $filtered = $entries->filter(function ($row) use ($start, $end) {
            if (!$start && !$end) return true;
            if (!$row['created_date']) return false;
            $day = substr($row['created_date'], 0, 10);
            return (!$start || $day >= $start) && (!$end || $day <= $end);
        })->sortByDesc('created_date')->values();
        $summary = $filtered->groupBy('class_type')->map(fn (Collection $rows, $category) => [
            'class_type' => $category,
            'full_payment' => $rows->sum('credit_cents') / 100,
            'payTotrainer' => $rows->sum('debit_cents') / 100,
            'net_amount' => ($rows->sum('credit_cents') - $rows->sum('debit_cents')) / 100,
        ])->values();

        return [
            'data' => $filtered->map(function ($row) {
                $row['full_payment'] = $row['credit_cents'] / 100;
                $row['payTotrainer'] = $row['debit_cents'] / 100;
                unset($row['credit_cents'], $row['debit_cents']);
                return $row;
            })->all(),
            'summary' => $summary->all(),
            'totalCredit' => $filtered->sum('credit_cents') / 100,
            'totalDebit' => $filtered->sum('debit_cents') / 100,
            'netAmount' => ($filtered->sum('credit_cents') - $filtered->sum('debit_cents')) / 100,
            'undatedCredit' => $undated->sum('credit_cents') / 100,
            'undatedDebit' => $undated->sum('debit_cents') / 100,
            'undatedCount' => $undated->count(),
        ];
    }
}
