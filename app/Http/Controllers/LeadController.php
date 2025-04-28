<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class LeadController extends Controller
{
    public function getLeads(Request $request)
    {
        $response = [];
        $resp = [];

        if ($request->has('id')) {
            $id = $request->input('id');

            $resp = DB::table('leads')->where('id', $id)->first();
            $response['trainers'] = DB::table('trainer')->where('is_trainer', 1)->orderByDesc('id')->get();
            $response['paymentDetails'] = DB::table('paymentdata')->where(['status' => 1, 'leadId' => $id, 'type' => 'lead'])->get();
        } else {
            $url = 'https://yogintra.com/wp-json/wp/v2/cf7/quote';

            $data = $this->getCurl($url);

            if (!empty($data) && !isset($data['code'])) {
                $formatted = collect($data)->map(function ($row) {
                    $newrow = unserialize($row['form_value']);
                    return [
                        'form_id'      => $row['form_id'],
                        'name'         => $newrow['your-name'] ?? null,
                        'number'       => $newrow['phone-number'] ?? null,
                        'email'        => $newrow['email-idl'] ?? null,
                        'country'      => $newrow['country'] ?? null,
                        'state'        => $newrow['state'] ?? null,
                        'city'         => $newrow['city'] ?? null,
                        'source'       => 'WEBSITE',
                        'class_type'   => $newrow['service-menu'][0] ?? null,
                        'call_from'    => $newrow['call-from'] ?? null,
                        'call_to'      => $newrow['call-to'] ?? null,
                        'message'      => $newrow['your-message'] ?? null,
                        'created_date' => $row['form_date'] ?? now(),
                        'created_by'   => 'sadmin',
                        'dump'         => json_encode($row),
                    ];
                });

                foreach ($formatted as $leadData) {
                    if (!DB::table('leads')->where('form_id', $leadData['form_id'])->exists()) {
                        DB::table('leads')->insert($leadData);
                    }
                }
            }

            $query = DB::table('leads')->where('status', 1);

            // Filters
            if ($request->filled('startDate')) {
                $query->whereDate('created_date', '>=', $request->input('startDate'));
            }

            if ($request->filled('endDate')) {
                $query->whereDate('created_date', '<=', $request->input('endDate'));
            }

            $roleId = session('admin_role_id') ?? null;
            $username = session('username') ?? 'guest';

            if ($roleId == 3) {
                $data1 = DB::table('leads')
                    ->where('status', 1)
                    ->where(function ($q) use ($username) {
                        $q->where('created_by', $username)->orWhere('created_by', '');
                    })
                    ->get();

                $data2 = DB::table('leads')->where('status', 1)->where('created_by', 'sadmin')->get();
                $data3 = DB::table('leads')->where('status', 1)->where('created_by', 'admin')->get();

                $resp = collect($data1)->merge($data2)->merge($data3)->sortByDesc('created_date')->values()->toArray();
            } else {
                $resp = $query->orderByDesc('created_date')->get()->toArray();
            }
        }

        foreach ($resp as $item) {
            if (isset($item->class_type)) {
                $item->class_type = str_replace(' Session', '', $item->class_type);
            }
        }

        if (!empty($resp)) {
            $response['success'] = 1;
            $response['data'] = $resp;
        } else {
            $response['success'] = 0;
            $response['message'] = 'No data found!';
        }

        return response()->json($response);
    }

    private function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    public function addLead(Request $request)
    {
        if ($request->has('name')) {

            $data = [
                'name' => $request->input('name', ''),
                'number' => $request->input('number', ''),
                'country' => $request->input('country', ''),
                'state' => $request->input('state', ''),
                'city' => $request->input('city', ''),
                'source' => $request->input('lead-source', ''),
                'email' => $request->input('email', ''),
                'class_type' => $request->input('class', ''),
                'call_from' => $request->input('call-from', ''),
                'call_to' => $request->input('call-to', ''),
                'message' => $request->input('client-message', ''),
                'created_date' => $request->input('date', ''),
                'package' => $request->input('package', ''),
                'quotation' => $request->input('quote', '0'),
                'dempay' => $request->input('demoPay', '0'),
                'attempt1' => $request->input('attempt1', '0'),
                'attempt2' => $request->input('attempt2', '0'),
                'attempt3' => $request->input('attempt3', '0'),
                'attempt1Remarks' => $request->input('remarks1', ''),
                'attempt2Remarks' => $request->input('remarks2', ''),
                'attempt3Remarks' => $request->input('remarks3', ''),
                'attempt1Date' => $request->input('atemptDate1', '0000-00-00 00:00:00'),
                'attempt2Date' => $request->input('atemptDate2', '0000-00-00 00:00:00'),
                'attempt3Date' => $request->input('atemptDate3', '0000-00-00 00:00:00'),
                'attendeeName' => $request->input('attendeeName', ''),
                'created_by' => session('username'),
                'status' => 1,
            ];

            $lead = DB::table('leads')->insertGetId($data);

            if ($lead) {
                return response()->json([
                    'success' => 1,
                    'message' => 'Inserted Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Some error occurred!'
                ]);
            }
        }
        return view('addLead');
    }

    public function viewProfile(Request $request)
    {
        return view('leadProfile');
    }

    public function getProfile(Request $request)
    {
        $id = $request->post('id');

        $lead = DB::table('leads')->select('leads.*', 'trainer.name as trainerName')
            ->leftJoin('trainer', 'trainer.id', '=', 'leads.trainer_id')
            ->where('leads.id', $id)
            ->first();

        $renewDetails = DB::table('package_renew_detail')->where('lead_id', $id)->get();

        if ($lead) {
            $lead->class_type = str_replace([' Session', ' Booking'], '', $lead->class_type);
        }

        $trainers = $this->getTrainers();
        $paymentDetails = $this->getPayments($id);

        return response()->json([
            'leads' => $lead,
            'renew_details' => $renewDetails,
            'trainers' => $trainers,
            'paymentDetails' => $paymentDetails,
        ]);
    }

    public function editProfile(Request $request)
    {
        $id = $request->query('id');
        $row = DB::table('leads')->find($id);

        return view('editLead', ['row' => $row]);
    }

    public function editLead(Request $request)
    {
        if ($request->has('leadId')) {
            $leadId = $request->post('leadId');

            $data = [
                'name'          => $request->post('name', ''),
                'number'        => $request->post('number', ''),
                'country'       => $request->post('country', ''),
                'state'         => $request->post('state', ''),
                'city'          => $request->post('city', ''),
                'source'        => $request->post('lead-source', ''),
                'email'         => $request->post('email', ''),
                'class_type'    => $request->post('class') ?? $request->post('hidden_class'),
                'call_from'     => $request->post('call-from', ''),
                'call_to'       => $request->post('call-to', ''),
                'message'       => $request->post('client-message', ''),
                'created_date'  => $request->post('date', ''),
                'package'       => $request->post('package', ''),
                'quotation'     => $request->post('quote', ''),
                'dempay'        => $request->post('demoPay', ''),
                'attempt1'      => $request->post('attempt1', 0),
                'attempt2'      => $request->post('attempt2', 0),
                'attempt3'      => $request->post('attempt3', 0),
                'attempt1Remarks' => $request->post('remarks1', ''),
                'attempt2Remarks' => $request->post('remarks2', ''),
                'attempt3Remarks' => $request->post('remarks3', ''),
                'attempt1Date'  => $request->post('atemptDate1', ''),
                'attempt2Date'  => $request->post('atemptDate2', ''),
                'attempt3Date'  => $request->post('atemptDate3', ''),
                'attendeeName'  => $request->post('attendeeName', ''),
                'trainer_id'    => $request->post('trainer', ''),
                'payTotrainer'  => $request->post('trainerPayment', ''),
                'payableAmount' => $request->post('payableAmount', ''),
                'demDate'       => !empty($request->post('demDate')) ? str_replace('T', ' ', $request->post('demDate')) : '0000-00-00 00:00:00',
                'trainerPayDate' => $request->post('trainerPayDate', ''),
                'payment_type'  => $request->post('payment_type', ''),
            ];

            // set status based on attempt1
            $status = ($request->post('attempt1') == 1) ? 3 : (($request->post('attempt1') == 2) ? 4 : 0);
            if (!empty($status)) {
                $data['status'] = $status;
            }

            // package end date
            if (!empty($request->post('packageEndDate'))) {
                $data['package_end_date'] = $request->post('packageEndDate');
            }

            // full payment or partition
            if ($request->post('payment_type') == 'Full Payment') {
                $data['full_payment'] = $request->post('totalPayAmount');
                $data['totalPayDate'] = str_replace('T', ' ', $request->post('totalPayDate'));
            } else if ($request->post('payment_type') == 'Partition Payment') {
                $fullPayments = $request->post('fullPayment');
                $data['full_payment'] = array_sum((array)$fullPayments);
            }

            $resp = DB::table('leads')->where('id', $leadId)->update($data);

            if ($resp) {
                if ($request->post('payment_type') == 'Partition Payment') {

                    $batchInsert = [];
                    $fullPayments = (array) $request->post('fullPayment');
                    $fullPaymentDates = (array) $request->post('fullPaymentDate');
                    $totalPayDate_change = null;

                    foreach ($fullPayments as $key => $row) {
                        if ($row > 0) {
                            $batchInsert[] = [
                                'leadId' => $leadId,
                                'amount' => $row,
                                'created_date' => str_replace('T', ' ', $fullPaymentDates[$key]),
                                'created_by' => session('username'),
                                'status' => 1,
                                'type' => 'lead',
                            ];
                        }
                        $totalPayDate_change = str_replace('T', ' ', $fullPaymentDates[$key]);
                    }

                    if (!empty($totalPayDate_change)) {
                        DB::table('leads')->where('id', $leadId)->update(['totalPayDate' => $totalPayDate_change]);
                    }

                    if (count($batchInsert) > 0) {
                        DB::table('paymentdata')
                            ->where('leadId', $leadId)
                            ->where('type', 'lead')
                            ->update(['status' => 0]);

                        DB::table('paymentdata')->insert($batchInsert);
                    }
                }

                return response()->json([
                    'success' => 1,
                    'message' => 'Inserted Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Some error occurred!'
                ]);
            }
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Some error occurred!'
            ]);
        }
    }

    public function changeReadStatus(Request $request)
    {
        if ($request->isMethod('post') && $request->has('id')) {
            $updated = DB::table('leads')
                ->where('id', $request->post('id'))
                ->update(['read_status' => 1]);

            if ($updated) {
                return response()->json([
                    'success' => 1,
                    'message' => 'Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Lead not found or already updated!'
                ]);
            }
        }
    }

    private function getTrainers()
    {
        return DB::table('trainer')->where('is_trainer', 1)->orderBy('id', 'desc')->get();
    }

    private function getPayments($leadId)
    {
        return DB::table('paymentdata')->where([
            ['status', '=', 1],
            ['leadId', '=', $leadId],
            ['type', '=', 'lead'],
        ])->get();
    }

    public function changeLeadStatus(Request $request)
    {
        $id = $request->input('id');
        $updated = DB::table('leads')->where('id', $id)->update(['status' => 2, 'created_by' => session('username')]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Status Changed Successfully' : 'No data found!'
        ]);
    }

    // telecalling
    public function getTellcalling(Request $request)
    {
        $query = DB::table('leads');

        // Filters
        if ($request->filled('startDate')) {
            $query->whereDate('created_date', '>=', $request->input('startDate'));
        }

        if ($request->filled('endDate')) {
            $query->whereDate('created_date', '<=', $request->input('endDate'));
        }

        $roleId = session('admin_role_id') ?? null;
        $username = session('username') ?? 'guest';

        $query->where('status', 2);

        if ($roleId == 3) {
            $query->where('created_by', $username);
        }

        $leads = $query->orderByDesc('created_date')->get();

        if ($leads->count() > 0) {
            // Modify class_type if needed
            $leads->transform(function ($item) {
                if (isset($item->class_type)) {
                    $item->class_type = str_replace(' Session', '', $item->class_type);
                }
                return $item;
            });

            return response()->json([
                'success' => 1,
                'data' => $leads
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'No data found!'
            ]);
        }
    }

    public function changeStatusToYoga(Request $request)
    {
        $id = $request->post('id');

        $dataM = DB::table('leads')->where('id', $id)->first();

        if ($dataM) {
            if ($dataM->class_type == 'Yoga Center') {
                $dataMain = [
                    'client_name'      => $dataM->name,
                    'client_number'    => $dataM->number,
                    'email'            => $dataM->email,
                    'country'          => $dataM->country,
                    'state'            => $dataM->state,
                    'city'             => $dataM->city,
                    'lead_transfer_id' => $dataM->id,
                ];

                DB::table('yoga')->insert($dataMain);

                $resp = DB::table('leads')->where('id', $id)->update(['status' => 6]);
            } else {
                $data = DB::table('leads')->select('attempt1', 'attempt2', 'attempt3')->where('id', $id)->first();
                $resp = false;
                if ($data && ($data->attempt1 == 1 || $data->attempt2 == 1 || $data->attempt3 == 1)) {
                    $resp = DB::table('leads')->where('id', $id)->update(['status' => 3]);
                }
            }

            if ($resp) {
                return response()->json([
                    'success' => 1,
                    'message' => 'Status Changed Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => 1,
                    'message' => 'No attempts for telecalling found!'
                ]);
            }
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Lead not found!'
            ]);
        }
    }

    public function changeStatusToLeads(Request $request)
    {
        $id = $request->post('id');

        $resp = DB::table('leads')->where('id', $id)->update(['status' => 1]);

        if ($resp) {
            return response()->json([
                'success' => 1,
                'message' => 'Status Changed Successfully'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'No data found!'
            ]);
        }
    }

    // customer
    public function getCustomer(Request $request)
    {
        $roleId = session('admin_role_id') ?? null;
        $username = session('username') ?? 'guest';

        $query = DB::table('leads')
            ->leftJoin('trainer', 'trainer.id', '=', 'leads.trainer_id')
            ->select(
                'leads.*',
                'trainer.name as trainerName',
                'trainer.created_date as trainer_created_date',
                'trainer.number as trainer_number',
                'trainer.salary'
            )
            ->where('leads.status', 3);

        if ($roleId == 3) {
            $query->whereIn('leads.created_by', [$username]);
        }

        // Apply filters
        if ($request->filled('startDate')) {
            $query->whereDate('leads.created_date', '>=', $request->input('startDate'));
        }

        if ($request->filled('endDate')) {
            $query->whereDate('leads.created_date', '<=', $request->input('endDate'));
        }

        if ($request->filled('due_type')) {
            if ($request->input('due_type') === 'full_payment') {
                $query->whereRaw('leads.payableAmount > leads.full_payment');
            } else {
                $query->where('leads.payTotrainer', 0);
            }
        }

        $customers = $query->orderByDesc('leads.created_date')->get();

        if ($customers->isNotEmpty()) {
            return response()->json([
                'success' => 1,
                'data' => $customers
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'No data found!'
        ]);
    }

    public function changeStatusToTelecalling(Request $request)
    {
        $id = $request->input('id');
        $updated = DB::table('leads')->where('id', $id)->update(['status' => 2]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Status Changed Successfully' : 'No data found!'
        ]);
    }

    public function changeStatusToTelecallingFromYogaCenter(Request $request)
    {
        $leadId = $request->input('lead_id');
        $yogaId = $request->input('id');

        $updated = DB::table('leads')->where('id', $leadId)->update(['status' => 2]);

        if ($updated) {
            DB::table('yoga')->where('id', $yogaId)->delete();
            return response()->json(['success' => 1, 'message' => 'Status Changed Successfully']);
        }

        return response()->json(['success' => 0, 'message' => 'No data found!']);
    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $deleted = DB::table('leads')->where('id', $id)->update(['status' => 0]);

        return response()->json([
            'success' => $deleted ? 1 : 0,
            'message' => $deleted ? 'Lead deleted Successfully' : 'No records found!'
        ]);
    }
}
