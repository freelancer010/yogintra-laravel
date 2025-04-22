<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class YogaController extends Controller
{
    public function getYoga(Request $request)
    {
        if ($request->isMethod('post') && $request->input('yoga') === 'getData') {
            // $query = DB::table('yoga')->where('status', 1);
            $query = DB::table('yoga');

            if ($request->filled('startDate')) {
                $query->whereDate('created_date', '>=', $request->input('startDate'));
            }

            if ($request->filled('endDate')) {
                $query->whereDate('created_date', '<=', $request->input('endDate'));
            }

            if ($request->filled('due_type')) {
                $query->whereColumn('totalPayAmount', '<', 'package');
            }

            $resp = $query->orderByDesc('created_date')->get();

            if ($resp->count() > 0) {
                return response()->json([
                    'success' => 1,
                    'data' => $resp
                ]);
            }

            return response()->json([
                'success' => 0,
                'message' => 'No data found!'
            ]);
        }
    }

    public function editEvents()
    {
        return view('editYoga');
    }

    public function addEvents(Request $request)
    {
        if ($request->filled('name')) {
            $data = [
                'client_name'     => $request->input('name'),
                'client_number'   => $request->input('number'),
                'email'           => $request->input('email'),
                'country'         => $request->input('country') ?? '',
                'state'           => $request->input('state') ?? '',
                'city'            => $request->input('city') ?? '',
                'class_type'      => $request->input('class_type'),
                'event_name'      => $request->input('eventName'),
                'created_date'    => $request->input('date') ?? now(),
                'created_by'      => Session::get('username') ?? '',
                's_date'          => $request->input('date') ?? $request->input('start_date'),
                'e_date'          => $request->input('date') ?? $request->input('end_date'),
                'package'         => $request->input('package'),
                'payment_type'    => $request->input('payment_type'),
                'lead_transfer_id' => $request->input('lead_transfer_id') ?? 0,
            ];

            if ($request->input('payment_type') === 'Full Payment') {
                $data['totalPayAmount'] = $request->input('totalPayAmount') ?? 0;
                $data['totalPayDate'] = $request->input('totalPayDate');
            } elseif ($request->input('payment_type') === 'Partition Payment') {
                $data['totalPayAmount'] = array_sum($request->input('fullPayment', [])) ?? 0;
                $data['totalPayDate'] = $request->input('fullPaymentDate')[0] ?? null;
            }

            $eventId = $request->input('eventId');
            if ($eventId) {
                $updated = DB::table('yoga')->where('id', $eventId)->update($data);
            } else {
                $eventId = DB::table('yoga')->insertGetId($data);
                $updated = true;
            }

            if ($updated && $request->input('payment_type') === 'Partition Payment') {
                $batchInsert = [];

                foreach ($request->input('fullPayment', []) as $key => $amount) {
                    if ($amount > 0) {
                        $batchInsert[] = [
                            'leadId'       => $eventId,
                            'amount'       => $amount,
                            'created_date' => str_replace('T', ' ', $request->input('fullPaymentDate')[$key]),
                            'created_by'   => Session::get('username'),
                            'status'       => 1,
                            'type'         => 'yoga',
                        ];
                    }
                }

                if ($eventId && count($batchInsert) > 0) {
                    if ($request->filled('eventId')) {
                        DB::table('paymentdata')->where([
                            'leadId' => $eventId,
                            'type'   => 'yoga',
                        ])->update(['status' => 0]);
                    }

                    DB::table('paymentdata')->insert($batchInsert);
                }
            }

            return response()->json([
                'success' => 1,
                'message' => 'Inserted Successfully'
            ]);
        }

        return view('addYoga');
    }

    public function getBookingProfile(Request $request)
    {
        if ($request->filled('bookingId')) {
            $id = $request->input('bookingId');

            $booking = DB::table('yoga')->where('id', $id)->first();
            if ($booking) {
                $booking = (array) $booking;
                $booking['paymentDetails'] = $this->getPayments($id);

                return response()->json([
                    'success'       => 1,
                    'data'          => $booking,
                    'renew_details' => DB::table('package_renew_detail')->where([
                        'lead_id' => $id,
                        'type'    => 'yoga',
                    ])->get(),
                ]);
            }

            return response()->json([
                'success' => 0,
                'message' => 'No data found!'
            ]);
        }

        return view('yogaDetails');
    }

    private function getPayments($leadId)
    {
        return DB::table('paymentdata')->where([
            'status' => 1,
            'leadId' => $leadId,
            'type'   => 'yoga',
        ])->get();
    }

    public function deleteData(Request $request)
    {
        $deleted = DB::table('yoga')->where('id', $request->input('id'))->delete();

        if ($deleted) {
            return response()->json([
                'success' => 1,
                'message' => 'Event deleted Successfully'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'No records found!'
        ]);
    }
}
