<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{
		return view('dashboard');
	}

	public function counts()
	{
		$adminRoleId = session('admin_role_id');
		$username = session('username');

		// LEADS
		if ($adminRoleId == 3) {
			$data1 = DB::table('leads')
				->where('status', 1)
				->where(function ($query) use ($username) {
					$query->where('created_by', $username)
						->orWhereNull('created_by')
						->orWhere('created_by', '');
				})
				->orderBy('created_date', 'desc')
				->get();

			$data2 = DB::table('leads')
				->where([
					['created_by', '=', 'sadmin'],
					['status', '=', 1]
				])
				->orderBy('created_date', 'desc')
				->get();

			$leads = $data1->merge($data2);
		} else {
			$leads = DB::table('leads')
				->where('status', 1)
				->orderBy('created_date', 'desc')
				->get();
		}

		// UNREAD LEADS
		if ($adminRoleId == 3) {
			$data1 = DB::table('leads')
				->where([
					['created_by', '=', $username],
					['status', '=', 1],
					['read_status', '=', 0]
				])
				->orderBy('created_date', 'desc')
				->get();

			$data2 = DB::table('leads')
				->where([
					['created_by', '=', 'sadmin'],
					['status', '=', 1],
					['read_status', '=', 0]
				])
				->orderBy('created_date', 'desc')
				->get();

			$unreadLeads = $data1->merge($data2);
		} else {
			$unreadLeads = DB::table('leads')
				->where([
					['status', '=', 1]
				])
				->orderBy('created_date', 'desc')
				->get();
		}

		// RECRUITS
		$unreadRecruits = DB::table('trainer')
			->where([
				['is_trainer', '=', 0],
				['status_trainer', '=', 1],
				['read_status', '=', 0]
			])
			->orderBy('id', 'desc')
			->get();

		$recruits = DB::table('trainer')
			->where([
				['is_trainer', '=', 0],
				['status_trainer', '=', 1]
			])
			->orderBy('id', 'desc')
			->get();

		// REJECTED
		$rejected = DB::table('leads')
			->where('status', 4)
			->orderBy('id', 'desc')
			->get();

		// TRAINERS
		$unreadTrainer = DB::table('trainer')
			->where([
				['is_trainer', '=', 1],
				['status_trainer', '=', 1],
				['read_status', '=', 0]
			])
			->orderBy('id', 'desc')
			->get();

		$trainer = DB::table('trainer')
			->where([
				['is_trainer', '=', 1],
				['status_trainer', '=', 1]
			])
			->orderBy('id', 'desc')
			->get();

		// YOGA & EVENTS
		$yoga = DB::table('yoga')->get();
		$events = DB::table('events')->get();

		// CUSTOMERS
		if ($adminRoleId == 3) {
			$data1 = DB::table('leads')
				->where([
					['created_by', '=', $username],
					['status', '=', 3]
				])
				->orderBy('created_date', 'desc')
				->get();

			$data2 = DB::table('leads')
				->where([
					['created_by', '=', 'sadmin'],
					['status', '=', 3]
				])
				->orderBy('created_date', 'desc')
				->get();

			$customer = $data1->merge($data2);
		} else {
			$customer = DB::table('leads')
				->where('status', 3)
				->orderBy('created_date', 'desc')
				->get();
		}

		// TELECALLER
		$telecallerQuery = DB::table('leads')->where('status', 2);
		if ($adminRoleId == 3) {
			$telecallerQuery->where('created_by', $username);
		}
		$telecaller = $telecallerQuery->orderBy('id', 'desc')->get();

		$financial = app(\App\Services\FinancialReport::class)->build();
		$totalCredit = $financial['totalCredit'];
		$totalDebit = $financial['totalDebit'];

		$result = [
			'lead' => $leads->count(),
			'unreadLeads' => $unreadLeads->count(),

			'customer' => $customer->count(),
			'telecaller' => $telecaller->count(),

			'recruits' => $recruits->count(),
			'unreadRecruits' => $unreadRecruits->count(),

			'trainer' => $trainer->count(),
			'unreadTrainer' => $unreadTrainer->count(),

			'rejected' => $rejected->count(),

			'yoga' => $yoga->count(),
			'events' => $events->count(),

			'totalCredit' => $totalCredit,
			'totalDebit' => $totalDebit,
			'undatedCount' => $financial['undatedCount'],
		];

		return response()->json($result);
	}
}
