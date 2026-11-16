<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = DB::table('ci_admin')->where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        if (!$admin->is_verify) {
            return back()->with('error', 'Please verify your email address!');
        }

        if (!$admin->is_active) {
            return back()->with('error', 'Account is disabled by Admin!');
        }

        $admin_role =  DB::table('ci_admin_roles')->where('admin_role_id', $admin->admin_role_id)->first();

        Session::put([
            'admin_id'      => $admin->admin_id,
            'username'      => $admin->username,
            'admin_role_id' => $admin->admin_role_id,
            'admin_role'    => $admin_role->admin_role_title,
            'is_supper'     => $admin->is_supper,
            'profile_image' => $admin->profile_image,
            'fullName'      => $admin->firstname . ' ' . $admin->lastname,
            'is_admin_login' => true
        ]);

        return redirect('dashboard');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    private function currentSuperAdmin()
    {
        abort_unless(session('is_supper') && session('admin_id'), 403);

        $admin = DB::table('ci_admin')->where('admin_id', session('admin_id'))->first();
        abort_unless($admin && $admin->is_supper == 1 && $admin->is_active == 1 && $admin->is_verify == 1, 403);

        return $admin;
    }

    public function showChangePasswordForm()
    {
        $this->currentSuperAdmin();
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $admin = $this->currentSuperAdmin();
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'max:72'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed', 'different:current_password'],
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        DB::table('ci_admin')->where('admin_id', $admin->admin_id)->update([
            'password' => Hash::make($validated['password']),
        ]);
        $request->session()->regenerate();

        return redirect()->route('account.password')->with('success', 'Your password has been changed successfully.');
    }
}
