<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    // public function __construct()
    // {
    //     // Custom auth_check() equivalent — optional
    //     if (!session()->has('logged_in')) {
    //         abort(403, 'Unauthorized');
    //     }
    // }

    public function index(Request $request)
    {
        $table = $request->query('type') === 'yoga' ? 'yoga-renewal' : 'renewal';
        return view($table);
    }

    public function getRenewal(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $type = $request->input('type');

        $table = ($type === 'yoga') ? 'yoga' : 'leads';

        $query = DB::table($table)->where('status', 5);

        if (session('admin_role_id') == 3) {
            $query->where('created_by', session('username'));
        }

        if (!empty($startDate)) {
            $query->whereDate('created_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('created_date', '<=', $endDate);
        }

        $results = $query->orderBy('created_date', 'desc')->get();

        if ($results->isNotEmpty()) {
            $results->transform(function ($item) {
                if (isset($item->class_type)) {
                    $item->class_type = str_replace(' Session', '', $item->class_type);
                }
                return $item;
            });

            return response()->json([
                'success' => 1,
                'data' => $results
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'No data found!'
        ]);
    }

    public function deleteData(Request $request)
    {
        $table = $request->query('type') === 'yoga' ? 'yoga' : 'leads';
        $id = $request->post('id');

        $updated = DB::table($table)->where('id', $id)->update(['status' => 0]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Renewal deleted Successfully' : 'No records found!'
        ]);
    }

    public function editRenewal(Request $request)
    {
        $type = $request->query('type');
        $table = ($type === 'yoga') ? 'yoga' : 'leads';
        $status = ($type === 'yoga') ? 1 : 3;
        $payField = ($type === 'yoga') ? 'totalPayAmount' : 'full_payment';
        $dateField = ($type === 'yoga') ? 'e_date' : 'package_end_date';

        $leadId = $request->input('leadId');
        $leadPreviousAmount = $request->input('leadPreviousAmount', 0);
        $renewalAmount = $request->input('renewalAmount', 0);

        $data = [
            $dateField => $request->input('renewalDate'),
            $payField => $leadPreviousAmount + $renewalAmount,
            'totalPayDate' => now(),
            'status' => $status,
            'renew_skip' => 0
        ];

        $updated = DB::table($table)->where('id', $leadId)->update($data);

        if ($updated) {
            DB::table('package_renew_detail')->updateOrInsert(
                ['lead_id' => $leadId],
                [
                    'renew_date' => $request->input('renewalDate'),
                    'renew_amount' => $renewalAmount,
                    'type' => $table,
                    'created_by' => session('username'),
                    'created_date' => now()
                ]
            );

            return response()->json([
                'success' => 1,
                'message' => 'Renewal Updated Successfully'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'No records found!'
        ]);
    }

    public function skipRenew(Request $request)
    {
        $type = $request->query('type');
        $table = ($type === 'yoga') ? 'yoga' : 'leads';
        $status = ($type === 'yoga') ? 1 : 3;

        $id = $request->post('id');

        $updated = DB::table($table)->where('id', $id)->update([
            'status' => $status,
            'renew_skip' => 1
        ]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Renewal Skipped Successfully' : 'No records found!'
        ]);
    }

    public function moveToRenew(Request $request)
    {
        $type = $request->query('type');
        $table = ($type === 'yoga') ? 'yoga' : 'leads';

        $id = $request->post('id');

        $updated = DB::table($table)->where('id', $id)->update([
            'status' => 5,
            'renew_skip' => 0
        ]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Data Recorded Successfully' : 'No records found!'
        ]);
    }
}
