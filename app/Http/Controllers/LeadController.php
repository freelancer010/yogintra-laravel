<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class LeadController extends Controller
{
    public function index()
    {
        return view('leads');
    }

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

            $user = Auth::user();
            $roleId = $user->role_id ?? null;
            $username = $user->username ?? 'guest';

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

        // Clean class_type field
        // foreach ($resp as &$item) {
        //     if (isset($item['class_type'])) {
        //         $item['class_type'] = str_replace(' Session', '', $item['class_type']);
        //     }
        // }
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

    public function add()
    {
        $data['users'] = User::where('role !=', 'Customer')->get();
        $data['lead_stages'] = LeadStage::all();

        return view('leads/add', $data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'nullable|email',
            'phone'        => 'nullable',
            'assigned_to'  => 'required',
            'stage_id'     => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lead = new Lead();
        $lead->name        = $request->input('name');
        $lead->email       = $request->input('email');
        $lead->phone       = $request->input('phone');
        $lead->assigned_to = $request->input('assigned_to');
        $lead->stage_id    = $request->input('stage_id');
        $lead->source      = $request->input('source');
        $lead->notes       = $request->input('notes');
        $lead->save();

        return redirect('leads')->with('success', 'Lead Added Successfully');
    }

    public function edit($id)
    {
        $data['lead'] = Lead::findOrFail($id);
        $data['users'] = User::where('role !=', 'Customer')->get();
        $data['lead_stages'] = LeadStage::all();

        return view('leads/edit', $data);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');

        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'nullable|email',
            'phone'        => 'nullable',
            'assigned_to'  => 'required',
            'stage_id'     => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lead = Lead::findOrFail($id);
        $lead->name        = $request->input('name');
        $lead->email       = $request->input('email');
        $lead->phone       = $request->input('phone');
        $lead->assigned_to = $request->input('assigned_to');
        $lead->stage_id    = $request->input('stage_id');
        $lead->source      = $request->input('source');
        $lead->notes       = $request->input('notes');
        $lead->save();

        return redirect('leads')->with('success', 'Lead Updated Successfully');
    }

    public function delete($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect('leads')->with('success', 'Lead Deleted Successfully');
    }

    public function ajax_list(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $query = Lead::query()->with(['assignedUser', 'stage']);

        if ($role != 'Admin') {
            $query->where('assigned_to', $user->id);
        }

        return datatables()->of($query)
            ->addColumn('assigned_to', fn($row) => $row->assignedUser->name ?? '')
            ->addColumn('stage', fn($row) => $row->stage->name ?? '')
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . url("leads/edit/" . $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <form action="' . url("leads/delete/" . $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
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

        $user = Auth::user();
        $roleId = $user->role_id ?? null;
        $username = $user->username ?? 'guest';

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


    // customer
    public function savedata(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $data = $request->only([
            'name',
            'number',
            'country',
            'state',
            'city',
            'lead-source',
            'email',
            'class',
            'call-from',
            'call-to',
            'client-message',
            'date',
            'package',
            'quote',
            'demoPay',
            'attendeeName',
            'trainer',
            'trainerPayment',
            'trainerPayDate',
            'packageEndDate',
            'totalPayDate',
            'payableAmount',
            'payment_type',
        ]);

        $lead = new Lead();
        $lead->fill([
            'name' => $data['name'],
            'number' => $data['number'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'source' => $data['lead-source'],
            'email' => $data['email'],
            'class_type' => $data['class'],
            'call_from' => $data['call-from'],
            'call_to' => $data['call-to'],
            'message' => $data['client-message'],
            'created_date' => $data['date'],
            'package' => $data['package'] ?? '0',
            'quotation' => $data['quote'],
            'dempay' => $data['demoPay'],
            'attempt1' => 1,
            'attendeeName' => $data['attendeeName'],
            'trainer_id' => $data['trainer'],
            'created_by' => Auth::user()->username ?? 'system',
            'payTotrainer' => $data['trainerPayment'] ?? 0,
            'trainerPayDate' => $data['trainerPayDate'],
            'demDate' => str_replace('T', ' ', $request->input('demDate')),
            'package_end_date' => $data['packageEndDate'] ?? null,
            'totalPayDate' => $data['totalPayDate'],
            'payableAmount' => $data['payableAmount'],
            'payment_type' => $data['payment_type'],
            'status' => 3,
        ]);

        if ($data['payment_type'] === 'Full Payment') {
            $lead->full_payment = $request->input('totalPayAmount');
            $lead->totalPayDate = str_replace('T', ' ', $request->input('totalPayDate'));
        } elseif ($data['payment_type'] === 'Partition Payment') {
            $lead->full_payment = array_sum($request->input('fullPayment', []));
        }

        if ($lead->save()) {
            if ($data['payment_type'] === 'Partition Payment') {
                $payments = [];
                foreach ($request->input('fullPayment', []) as $key => $amount) {
                    if ($amount > 0) {
                        $payments[] = [
                            'leadId' => $lead->id,
                            'amount' => $amount,
                            'created_date' => $request->input('fullPaymentDate')[$key],
                            'created_by' => Auth::user()->username,
                            'status' => 1,
                            'type' => 'lead',
                        ];
                    }
                }

                if (!empty($payments)) {
                    PaymentData::where(['leadId' => $lead->id, 'type' => 'lead'])->update(['status' => 0]);
                    PaymentData::insert($payments);
                }
            }

            return response()->json(['success' => 1, 'message' => 'Customer Added Successfully']);
        }

        return response()->json(['success' => 0, 'message' => 'Unable to add customer!']);
    }

    public function getCustomer(Request $request)
    {
        $user = Auth::user();
        $roleId = $user->role_id ?? null;
        $username = $user->username ?? 'guest';

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
        $updated = Lead::where('id', $id)->update(['status' => 2]);

        return response()->json([
            'success' => $updated ? 1 : 0,
            'message' => $updated ? 'Status Changed Successfully' : 'No data found!'
        ]);
    }

    public function changeStatusToTelecallingFromYogaCenter(Request $request)
    {
        $leadId = $request->input('lead_id');
        $yogaId = $request->input('id');

        $updated = Lead::where('id', $leadId)->update(['status' => 2]);

        if ($updated) {
            DB::table('yoga')->where('id', $yogaId)->delete();
            return response()->json(['success' => 1, 'message' => 'Status Changed Successfully']);
        }

        return response()->json(['success' => 0, 'message' => 'No data found!']);
    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $deleted = Lead::where('id', $id)->update(['status' => 0]);

        return response()->json([
            'success' => $deleted ? 1 : 0,
            'message' => $deleted ? 'Lead deleted Successfully' : 'No records found!'
        ]);
    }
}
