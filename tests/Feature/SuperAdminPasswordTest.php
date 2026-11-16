<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SuperAdminPasswordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('ci_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->string('password');
            $table->boolean('is_supper')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verify')->default(true);
        });
        DB::table('ci_admin')->insert([
            ['admin_id' => 1, 'password' => Hash::make('OldPassword123!'), 'is_supper' => 1],
            ['admin_id' => 2, 'password' => Hash::make('OtherPassword123!'), 'is_supper' => 0],
        ]);
    }

    private function adminSession(): array
    {
        return ['admin_id' => 1, 'admin_role_id' => 1, 'is_supper' => 1, 'fullName' => 'Test Admin'];
    }

    public function test_guests_cannot_access_password_page(): void
    {
        $this->get('/account/password')->assertRedirect('/login');
        $this->post('/account/password')->assertRedirect('/login');
    }

    public function test_regular_admin_cannot_access_or_submit(): void
    {
        $session = ['admin_id' => 2, 'admin_role_id' => 2, 'is_supper' => 0];
        $this->withSession($session)->get('/account/password')->assertForbidden();
        $this->withSession($session)->post('/account/password')->assertForbidden();
    }

    public function test_database_super_admin_flag_is_required(): void
    {
        DB::table('ci_admin')->where('admin_id', 1)->update(['is_supper' => 0]);
        $this->withSession($this->adminSession())->get('/account/password')->assertForbidden();
        $this->withSession($this->adminSession())->post('/account/password')->assertForbidden();
    }

    public function test_super_admin_sees_password_form_and_menu(): void
    {
        $this->withSession($this->adminSession())->get('/account/password')
            ->assertOk()->assertSee('Current password')->assertSee('Confirm new password')->assertSee('Open account menu');
    }

    public function test_wrong_current_password_does_not_change_hash(): void
    {
        $hash = DB::table('ci_admin')->where('admin_id', 1)->value('password');
        $this->withSession($this->adminSession())->from('/account/password')->post('/account/password', [
            'current_password' => 'WrongPassword', 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect('/account/password')->assertSessionHasErrors('current_password');
        $this->assertSame($hash, DB::table('ci_admin')->where('admin_id', 1)->value('password'));
    }

    public function test_confirmation_and_minimum_length_are_required(): void
    {
        $this->withSession($this->adminSession())->post('/account/password', [
            'current_password' => 'OldPassword123!', 'password' => 'short', 'password_confirmation' => 'different',
        ])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('OldPassword123!', DB::table('ci_admin')->where('admin_id', 1)->value('password')));
    }

    public function test_password_is_hashed_and_only_own_account_is_updated(): void
    {
        $otherHash = DB::table('ci_admin')->where('admin_id', 2)->value('password');
        $this->withSession($this->adminSession())->post('/account/password', [
            'admin_id' => 2, 'current_password' => 'OldPassword123!', 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect('/account/password')->assertSessionHas('success');
        $hash = DB::table('ci_admin')->where('admin_id', 1)->value('password');
        $this->assertTrue(Hash::check('NewPassword123!', $hash));
        $this->assertFalse(Hash::check('OldPassword123!', $hash));
        $this->assertSame($otherHash, DB::table('ci_admin')->where('admin_id', 2)->value('password'));
    }
}
