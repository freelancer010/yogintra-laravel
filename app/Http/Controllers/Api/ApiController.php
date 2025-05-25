<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getAllTrainers()
    {
        $trainers = DB::table('trainer')
            ->where('show_trainer', 1)
            ->where('is_trainer', 1)
            ->where('status_trainer', 1)
            ->where('is_featured_trainer', 0)
            ->orderByDesc('id')
            ->get();

        return response()->json($trainers);
    }

    public function getAllFeaturedTrainersWithLimit()
    {
        $trainers = DB::table('trainer')
            ->where('show_trainer', 1)
            ->where('is_trainer', 1)
            ->where('status_trainer', 1)
            ->where('is_featured_trainer', 1)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return response()->json($trainers);
    }

    public function addLead(Request $request)
    {
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
            'created_date'
        ]);

        $leadData = [
            'name'          => $data['name'] ?? '',
            'number'        => $data['number'] ?? '',
            'country'       => $data['country'] ?? '',
            'state'         => $data['state'] ?? '',
            'city'          => $data['city'] ?? '',
            'source'        => $data['lead-source'] ?? '',
            'email'         => $data['email'] ?? '',
            'class_type'    => $data['class'] ?? '',
            'call_from'     => $data['call-from'] ?? '',
            'call_to'       => $data['call-to'] ?? '',
            'message'       => $data['client-message'] ?? '',
            'created_date'  => $data['created_date'] ?? now(),
        ];

        DB::table('leads')->insert($leadData);

        return response()->json(1);
    }

    public function addRecruitment(Request $request)
    {
        $data = $request->only([
            'name',
            'number',
            'email',
            'country',
            'state',
            'city',
            'dob',
            'education',
            'certification',
            'Other_Certificate',
            'experience',
            'address'
        ]);

        $recruitData = [
            'name'              => $data['name'] ?? '',
            'number'            => $data['number'] ?? '',
            'email'             => $data['email'] ?? '',
            'country'           => $data['country'] ?? '',
            'state'             => $data['state'] ?? '',
            'city'              => $data['city'] ?? '',
            'dob'               => $data['dob'] ?? '',
            'Education'         => $data['education'] ?? '',
            'certification'     => $data['certification'] ?? '',
            'Other_Certificate' => $data['Other_Certificate'] ?? '',
            'experience'        => $data['experience'] ?? '',
            'address'           => $data['address'] ?? '',
            'is_trainer'        => 0,
            'created_date'      => now(),
            'read_status'       => 0,
            'status_trainer'    => 1,
            'show_trainer'      => 1
        ];

        DB::table('trainer')->insert($recruitData);

        return response()->json(1);
    }

    public function addEventData(Request $request)
    {
        $data = $request->only([
            'client_name',
            'event_name',
            'client_number',
            'country',
            'state',
            'city',
            'package',
            'email',
            'class_type',
            'totalPayAmount'
        ]);

        $eventData = [
            'client_name'       => $data['client_name'],
            'event_name'        => $data['event_name'],
            'client_number'     => $data['client_number'],
            'created_date'      => now(),
            'country'           => $data['country'],
            'state'             => $data['state'],
            'city'              => $data['city'],
            'package'           => $data['package'],
            'email'             => $data['email'],
            'class_type'        => $data['class_type'],
            'payment_type'      => 'Full Payment',
            'totalPayAmount'    => $data['totalPayAmount'],
        ];

        DB::table('events')->insert($eventData);

        return response()->json(1);
    }

    public function searchTrainers(Request $request)
    {
        $search = $request->input('data');

        $trainers = DB::table('trainer')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%")
                    ->orWhere('state', 'like', "%$search%")
                    ->orWhere('country', 'like', "%$search%");
            })
            ->where('is_trainer', 1)
            ->where('show_trainer', 1)
            ->where('status_trainer', 1)
            ->where('is_featured_trainer', 0)
            ->get();

        return response()->json($trainers);
    }
}
