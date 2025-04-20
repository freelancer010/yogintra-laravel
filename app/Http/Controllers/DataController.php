<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function viewAllData()
    {
        return view('allData');
    }

    public function allData(Request $request)
    {
        $query = DB::table('leads')
            ->whereIn('status', [1, 2, 3, 4, 5]);

        if ($request->startDate) {
            $query->whereDate('created_date', '>=', $request->startDate);
        }

        if ($request->endDate) {
            $query->whereDate('created_date', '<=', $request->endDate);
        }

        $leads = $query->orderBy('created_date', 'desc')->get()->toArray();

        foreach ($leads as &$item) {
            $item->class_type = $this->sanitizeClassType($item->class_type ?? '');
        }

        $yoga = DB::table('yoga')->orderBy('created_date', 'desc')->get();

        if (count($leads) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $leads,
                'yoga' => $yoga
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'No data found!'
        ]);
    }

    public function rejected()
    {
        $query = DB::table('leads')->where('status', 4);

        if (session('admin_role_id') == 3) {
            $query->where('created_by', session('username'));
        }

        $rejected = $query->orderBy('id', 'desc')->get();

        foreach ($rejected as &$item) {
            $item->class_type = $this->sanitizeClassType($item->class_type ?? '');
        }

        return response()->json([
            'success' => count($rejected) > 0 ? 1 : 0,
            'data' => $rejected,
            'message' => count($rejected) > 0 ? null : 'No data found!'
        ]);
    }

    public function rejectedView()
    {
        return view('rejected');
    }

    public function toReject(Request $request)
    {
        $updated = DB::table('leads')->where('id', $request->id)->update([
            'status' => 4
        ]);

        return response()->json([
            'success' => 1,
            'message' => $updated ? 'Status Changed Successfully' : 'No attempts for telecalling found!'
        ]);
    }

    public function restore(Request $request)
    {
        $updated = DB::table('leads')->where('id', $request->id)->update([
            'status' => 1,
            'attempt1' => 0
        ]);

        return response()->json([
            'success' => 1,
            'message' => $updated ? 'Status Changed Successfully' : 'No attempts for telecalling found!'
        ]);
    }

    private function sanitizeClassType($type)
    {
        return str_replace(
            [' Session', ' Booking', 'Private Online Yoga', 'Private Online', 'Group Online Yoga', 'Group Online'],
            ['', '', 'Private Online', 'Private Online Yoga', 'Group Online', 'Group Online Yoga'],
            $type
        );
    }
}
