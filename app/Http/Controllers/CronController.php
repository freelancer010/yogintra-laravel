<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CronController extends Controller
{
    public function updateRenewData()
    {
        $now = Carbon::now();

        DB::table('leads')
            ->where('package_end_date', '>=', $now)
            ->where('status', 5)
            ->update(['status' => 3]);

        DB::table('yoga')
            ->where('e_date', '>=', $now)
            ->where('status', 5)
            ->update(['status' => 1]);

        DB::table('leads')
            ->where('package_end_date', '<', $now)
            ->where('renew_skip', 0)
            ->whereNotNull('package_end_date')
            ->update(['status' => 5]);

        DB::table('yoga')
            ->where('e_date', '<', $now)
            ->where('renew_skip', 0)
            ->whereNotNull('package_end_date')
            ->update(['status' => 5]);

        return response()->json([
            'success' => 1,
            'message' => 'Lead and yoga statuses updated successfully.'
        ]);
    }
}
