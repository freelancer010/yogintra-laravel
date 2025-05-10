<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct() {}

    public function ledger(Request $request)
    {
        if ($request->isMethod('post') && $request->has('class_type')) {
            $where = [];

            if ($request->class_type !== 'all') {
                $where[] = "temp.class_type = '{$request->class_type}'";
            }

            if (!empty($request->startDate)) {
                $where[] = "DATE(temp.created_date) >= '{$request->startDate}'";
            }

            if (!empty($request->endDate)) {
                $where[] = "DATE(temp.created_date) <= '{$request->endDate}'";
            }

            $whereClause = $where ? ' AND ' . implode(' AND ', $where) : '';

            $query = "SELECT * FROM 
								(SELECT
									`leads`.`name`,
									`leads`.`full_payment`,
									`leads`.`created_date`,
									`leads`.`payTotrainer`,
									`leads`.`class_type`,
									`trainer`.`name` AS `trainerName`,
									`trainer`.`created_date` AS `trainer_created_date`,
									`trainer`.`salary`
								FROM
									`leads`
								LEFT JOIN `trainer` ON `trainer`.`id` = `leads`.`trainer_id`
								WHERE
									`status` IN (3)
							UNION ALL
								SELECT
									`yoga`.`client_name` as name,
									`yoga`.`totalPayAmount` as full_payment,
									`yoga`.`created_date`,
									0 `payTotrainer`,
									'Yoga Center' `class_type`,
									'' `trainerName`,
									'' `trainer_created_date`,
									0 `salary`
								FROM
									`yoga`
								WHERE
									`status` = 1
							) as temp WHERE 1=1 $whereClause ORDER BY temp.created_date DESC";

            $results = DB::select($query);

            return response()->json([
                'success' => count($results) > 0 ? 1 : 0,
                'data' => $results,
                'message' => count($results) > 0 ? null : 'No data found!',
            ]);
        }

        return view('ledger');
    }

    public function summary(Request $request)
    {
        if ($request->isMethod('post') && $request->has('class_type')) {
            $filterWhere = '';

            if (!empty($request->startDate)) {
                $filterWhere .= " AND created_date >= '{$request->startDate}'";
            }

            if (!empty($request->endDate)) {
                $filterWhere .= " AND created_date <= '{$request->endDate}'";
            }

            $query = "
                (SELECT class_type, SUM(full_payment) AS full_payment, SUM(payTotrainer) AS payTotrainer
                    FROM leads
                    WHERE status NOT IN (0,6) AND class_type != '' $filterWhere
                    GROUP BY class_type)

                UNION ALL

                (SELECT 'Expense' AS class_type, 0 AS full_payment, SUM(expenseAmount) AS payTotrainer
                    FROM expense
                    WHERE 1=1 $filterWhere)

                UNION ALL

                (SELECT class_type, SUM(totalPayAmount) AS full_payment, 0 AS payTotrainer
                    FROM events
                    WHERE 1=1 $filterWhere
                    GROUP BY class_type)

                UNION ALL

                (SELECT 'Client Yoga Center' AS class_type, SUM(totalPayAmount) AS full_payment, 0 AS payTotrainer
                    FROM yoga
                    WHERE 1=1 $filterWhere
                    GROUP BY class_type)
            ";

            $rows = DB::select($query);

            $combined = [];

            foreach ($rows as $row) {
                $type = $row->class_type;
                $row->full_payment = (int) $row->full_payment;
                $row->payTotrainer = (int) $row->payTotrainer;

                if (in_array($type, ['Home Visit Yoga'])) {
                    $type = 'Home Visit Yoga';
                } elseif (in_array($type, ['Private Online Yoga'])) {
                    $type = 'Private Online Yoga';
                } elseif (in_array($type, ['Group Online Yoga'])) {
                    $type = 'Group Online Yoga';
                } elseif ($type === 'Client Yoga Center' || $type === 'Yoga Center') {
                    $type = 'Yoga Center';
                }

                if (isset($combined[$type])) {
                    $combined[$type]['full_payment'] += $row->full_payment;
                    $combined[$type]['payTotrainer'] += $row->payTotrainer;
                } else {
                    $combined[$type] = [
                        'class_type' => $type,
                        'full_payment' => $row->full_payment,
                        'payTotrainer' => $row->payTotrainer,
                    ];
                }
            }

            $final = array_values($combined);

            return response()->json([
                'success' => count($final) > 0 ? 1 : 0,
                'data' => $final,
                'totalCredit' => array_sum(array_column($final, 'full_payment')),
                'totalDebit' => array_sum(array_column($final, 'payTotrainer')),
                'message' => count($final) > 0 ? null : 'No data found!',
            ]);
        }

        return view('summary');
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
