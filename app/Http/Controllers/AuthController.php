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
}
