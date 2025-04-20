<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct() {}

    public function index($type = '')
    {
        Session::put('filter_type', $type);
        Session::put('filter_keyword', '');
        Session::put('filter_status', '');

        $data['admin_roles'] = DB::table('ci_admin_roles')->get();
        $data['title'] = 'Admin List';

        return view('admin.view', $data);
    }

    public function filterData(Request $request)
    {
        Session::put('filter_type', $request->type);
        Session::put('filter_status', $request->status);
        Session::put('filter_keyword', $request->keyword);
    }

    public function listData()
    {
        $query = DB::table('ci_admin')
            ->join('ci_admin_roles', 'ci_admin_roles.admin_role_id', '=', 'ci_admin.admin_role_id')
            ->where('ci_admin.is_supper', '!=', 1)
            ->orderByDesc('ci_admin.admin_id');

        // Apply filters from session
        if (Session::get('filter_type') != '') {
            $query->where('ci_admin.admin_role_id', Session::get('filter_type'));
        }

        if (Session::get('filter_status') != '') {
            $query->where('ci_admin.is_active', Session::get('filter_status'));
        }

        $filterKeyword = Session::get('filter_keyword');

        if (!empty($filterKeyword)) {
            $query->where(function ($q) use ($filterKeyword) {
                $q->where('ci_admin_roles.admin_role_title', 'like', "%{$filterKeyword}%")
                    ->orWhere('ci_admin.firstname', 'like', "%{$filterKeyword}%")
                    ->orWhere('ci_admin.lastname', 'like', "%{$filterKeyword}%")
                    ->orWhere('ci_admin.email', 'like', "%{$filterKeyword}%")
                    ->orWhere('ci_admin.mobile_no', 'like', "%{$filterKeyword}%")
                    ->orWhere('ci_admin.username', 'like', "%{$filterKeyword}%");
            });
        }

        $data['info'] = $query->get();

        return view('admin.list', $data);
    }

    public function changeStatus(Request $request)
    {
        DB::table('admin')
            ->where('admin_id', $request->id)
            ->update(['is_active' => $request->status]);
    }

    public function add(Request $request)
    {
        $data['admin_roles'] = DB::table('ci_admin_roles')->get();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|alpha_num|unique:ci_admin,username',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'mobile_no' => 'required',
                'password' => 'required',
                'role' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $insert = [
                'admin_role_id' => $request->role,
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'password' => Hash::make($request->password),
                'is_active' => 1,
                'last_login' => now(),
                'token'     =>  '',
                'password_reset_code'   =>  '',
                'last_ip'       =>  '',
                'created_at'    => now(),
                'updated_at'    => now()
            ];

            if ($request->hasFile('profileImage')) {
                $file = $request->file('profileImage');
                $path = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');
                $insert['profile_image'] = 'storage/' . $path;
            }

            DB::table('ci_admin')->insert($insert);

            Session::flash('success', 'Admin has been added successfully!');
            return redirect()->route('admin.view');
        }

        return view('admin.add', $data);
    }

    public function edit(Request $request, $id = null)
    {

        $data['admin_roles'] = DB::table('admin_roles')->get();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|alpha_num',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'mobile_no' => 'required',
                'password' => 'nullable|min:3',
                'role' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $update = [
                'admin_role_id' => $request->role,
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'is_active' => 1,
                'updated_at' => now()
            ];

            if ($request->filled('password')) {
                $update['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('profileImage')) {
                $file = $request->file('profileImage');
                $path = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');
                $update['profile_image'] = 'storage/' . $path;
            }

            DB::table('admin')->where('admin_id', $id)->update($update);

            Session::flash('success', 'Admin has been updated successfully!');
            return redirect()->route('admin.index');
        } elseif (!$id) {
            return redirect()->route('admin.index');
        } else {
            $data['admin'] = DB::table('admin')->where('admin_id', $id)->first();

            return view('admin.edit', $data);
        }
    }

    public function checkUsername(Request $request, $id = 0)
    {
        $exists = DB::table('admin')
            ->where('username', $request->username)
            ->where('admin_id', '!=', $id)
            ->exists();

        return response()->json(!$exists);
    }

    public function delete($id)
    {

        DB::table('admin')->where('admin_id', $id)->delete();

        Session::flash('success', 'User has been Deleted Successfully.');
        return redirect()->route('admin.index');
    }
}
