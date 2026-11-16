<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct() {}

    private function reportFilters(Request $request): array
    {
        return $request->validate([
            'class_type' => ['nullable', 'string', 'max:100'],
            'startDate' => ['nullable', 'date_format:Y-m-d'],
            'endDate' => array_filter(['nullable', 'date_format:Y-m-d', $request->filled('startDate') ? 'after_or_equal:startDate' : null]),
        ]);
    }

    private function financialResponse(Request $request, bool $summary)
    {
        $filters = $this->reportFilters($request);
        $report = app(\App\Services\FinancialReport::class)->build(
            $filters['startDate'] ?? null, $filters['endDate'] ?? null,
            ($filters['class_type'] ?? '') ?: 'all'
        );
        $rows = $summary ? $report['summary'] : $report['data'];
        unset($report['summary']);
        return response()->json(array_merge($report, [
            'success' => count($rows) > 0 ? 1 : 0,
            'data' => $rows,
            'message' => count($rows) > 0 ? null : 'No data found!',
        ]));
    }

    public function ledger(Request $request)
    {
        return $request->isMethod('post') ? $this->financialResponse($request, false) : view('ledger');
    }

    public function summary(Request $request)
    {
        return $request->isMethod('post') ? $this->financialResponse($request, true) : view('summary');
    }

    public function expenses(Request $request)
    {
        if ($request->isMethod('post') && $request->has('class_type')) {
            $query = DB::table('expense');

            if (!empty($request->startDate)) {
                $query->whereDate('created_date', '>=', $request->startDate);
            }

            if (!empty($request->endDate)) {
                $query->whereDate('created_date', '<=', $request->endDate);
            }

            $expenses = $query->orderBy('created_date', 'desc')->get();

            return response()->json([
                'success' => count($expenses) > 0 ? 1 : 0,
                'data' => $expenses,
                'message' => count($expenses) > 0 ? null : 'No data found!',
            ]);
        }

        return view('officeExpences');
    }

    public function addExpenses(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->only(['expenseType', 'expenseAmount', 'note', 'payee']);
            $data['created_by'] = session('username') ?? 'Unknown';
            $data['created_date'] = $request->input('expenseDate');

            $result = DB::table('expense')->insert($data);

            return response()->json([
                'success' => $result ? 1 : 0,
                'message' => $result ? 'Your Expense Added Successfully' : 'Some error occurred!',
            ]);
        }

        return view('addExpenses');
    }

    public function editExpenses(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $data = $request->only(['expenseType', 'expenseAmount', 'note', 'payee']);
            $data['created_date'] = $request->input('expenseDate');

            $updated = DB::table('expense')->where('id', $id)->update($data);

            return response()->json([
                'success' => $updated ? 1 : 0,
                'message' => $updated ? 'Expense Updated Successfully' : 'Some error occurred!',
            ]);
        }

        $expense = DB::table('expense')->find($id);
        return view('editExpenses', compact('expense'));
    }

    public function deleteExpenses($id)
    {
        return DB::table('expense')->where('id', $id)->delete();
    }
}
