<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function getEvent(Request $request)
    {
        if ($request->input('event') === 'getData') {
            $query = DB::table('events');

            if ($request->filled('startDate')) {
                $query->whereDate('created_date', '>=', $request->startDate);
            }

            if ($request->filled('endDate')) {
                $query->whereDate('created_date', '<=', $request->endDate);
            }

            if ($request->filled('due_type')) {
                $query->whereColumn('totalPayAmount', '<', 'package');
            }

            $events = $query->get()->map(function ($event) {
                if ($event->class_type === 'TTC') {
                    $event->class_type = 'TTC';
                }
                return $event;
            });

            return response()->json([
                'success' => $events->count() > 0 ? 1 : 0,
                'data' => $events,
                'message' => $events->count() > 0 ? null : 'No data found!'
            ]);
        }
    }

    public function editEvents()
    {
        return view('editEvents');
    }

    public function addEvents(Request $request)
    {
        if ($request->filled('name')) {
            $data = [
                'client_name'   => $request->input('name'),
                'event_name'    => $request->input('eventName'),
                'client_number' => $request->input('number'),
                'country'       => $request->input('country'),
                'state'         => $request->input('state'),
                'city'          => $request->input('city'),
                'email'         => $request->input('email'),
                'class_type'    => $request->input('class'),
                'created_date'  => $request->input('date') ?: now(),
                'package'       => $request->input('package'),
                'payment_type'  => $request->input('payment_type')
            ];

            if ($data['payment_type'] === 'Full Payment') {
                $data['totalPayAmount'] = $request->input('totalPayAmount');
                $data['totalPayDate'] = $request->input('totalPayDate');
            } elseif ($data['payment_type'] === 'Partition Payment') {
                $data['totalPayAmount'] = array_sum($request->input('fullPayment', []));
                $data['totalPayDate'] = $request->input('fullPaymentDate')[0] ?? now();
            }

            if ($request->filled('eventId')) {
                DB::table('events')->where('id', $request->input('eventId'))->update($data);
                $eventId = $request->input('eventId');
            } else {
                $eventId = DB::table('events')->insertGetId($data);
            }

            // Handle partition payment
            if ($data['payment_type'] === 'Partition Payment') {
                $batchInsert = [];
                foreach ($request->input('fullPayment', []) as $key => $amount) {
                    if ($amount > 0) {
                        $batchInsert[] = [
                            'leadId'       => $eventId,
                            'amount'       => $amount,
                            'created_date' => str_replace('T', ' ', $request->input('fullPaymentDate')[$key]),
                            'created_by'   => session('username'),
                            'status'       => 1,
                            'type'         => 'event',
                        ];
                    }
                }

                if ($request->filled('eventId')) {
                    DB::table('paymentdata')
                        ->where(['leadId' => $eventId, 'type' => 'event'])
                        ->update(['status' => 0]);
                }

                if (!empty($batchInsert)) {
                    DB::table('paymentdata')->insert($batchInsert);
                }
            }

            return response()->json([
                'success' => 1,
                'message' => 'Inserted Successfully'
            ]);
        }

        return view('addEvents');
    }

    public function getBookingProfile(Request $request)
    {
        if ($request->filled('bookingId')) {
            $event = DB::table('events')->where('id', $request->bookingId)->first();

            if ($event) {
                $event->paymentDetails = DB::table('paymentdata')
                    ->where(['leadId' => $event->id, 'type' => 'event', 'status' => 1])
                    ->get();

                return response()->json([
                    'success' => 1,
                    'data' => $event
                ]);
            }

            return response()->json([
                'success' => 0,
                'message' => 'No data found!'
            ]);
        }

        return view('bookingDetails');
    }

    public function deleteData(Request $request)
    {
        $deleted = DB::table('events')->where('id', $request->id)->delete();

        return response()->json([
            'success' => $deleted ? 1 : 0,
            'message' => $deleted ? 'Event deleted Successfully' : 'No records found!'
        ]);
    }
}
