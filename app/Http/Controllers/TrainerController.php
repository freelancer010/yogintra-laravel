<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class TrainerController extends Controller
{
    public function __construct() {}

    public function getRecruiter(Request $request)
    {
        if ($request->has('recruiter')) {
            $url = 'https://yogintra.com/wp-json/wp/v2/cf7/recruitement';
            $data = Http::get($url)->json();

            if (!empty($data) && (!isset($data['code']) || $data['code'] !== 'empty_product')) {
                $mappedData = array_map(function ($row) {
                    $newrow = unserialize($row['form_value']);

                    return [
                        'form_id' => $row['form_id'],
                        'form_post_id' => $row['form_post_id'],
                        'created_date' => $row['form_date'],
                        'cfdb7_status' => $newrow['cfdb7_status'] ?? null,
                        'name' => $newrow['your-name'] ?? null,
                        'number' => $newrow['phone-number'] ?? null,
                        'email' => $newrow['email-idl'] ?? null,
                        'dob' => $newrow['dob'] ?? null,
                        'gender' => $newrow['gender'][0] ?? null,
                        'country' => $newrow['country'] ?? null,
                        'state' => $newrow['state'] ?? null,
                        'city' => $newrow['city'] ?? null,
                        'Education' => $newrow['Education'] ?? null,
                        'experience' => $newrow['experience'] ?? null,
                        'certification' => $newrow['certification'] ?? null,
                        'Other_Certificate' => $newrow['Other-Certificate'] ?? null,
                        'address' => $newrow['your-address'] ?? null,
                        'vx_width' => $newrow['vx_width'] ?? null,
                        'vx_height' => $newrow['vx_height'] ?? null,
                        'cv_filecfdb7_file' => '',
                        'cf7mls_step_1' => $newrow['cf7mls_step-1'] ?? null,
                        'cf7mls_step_2' => $newrow['cf7mls_step-2'] ?? null,
                        'cf7mls_step_3' => $newrow['cf7mls_step-3'] ?? null,
                        'dump' => json_encode($newrow),
                    ];
                }, $data);

                foreach ($mappedData as $row) {
                    if (!DB::table('trainer')->where('form_id', $row['form_id'])->exists()) {
                        DB::table('trainer')->insert($row);
                    }
                }
            }

            $query = DB::table('trainer')->where('is_trainer', 0)->where('name', '<>', '')->where('status_trainer', 1);

            if ($request->startDate) {
                $query->whereDate('created_date', '>=', $request->startDate);
            }

            if ($request->endDate) {
                $query->whereDate('created_date', '<=', $request->endDate);
            }

            $result = $query->orderByDesc('created_date')->get();

            return response()->json([
                'success' => $result->count() > 0 ? 1 : 0,
                'data' => $result,
                'message' => $result->count() > 0 ? '' : 'No data found!',
            ]);
        }
    }

    public function addRecruit()
    {
        return view('addRecruits');
    }

    public function viewProfile()
    {
        return view('trainerProfile');
    }

    public function getProfileDetails(Request $request)
    {
        if ($request->id) {
            $trainer = DB::table('trainer')->where('id', $request->id)->first();
            return response()->json(['success' => 1, 'data' => $trainer]);
        }
    }

    public function savedata(Request $request)
    {
        if ($request->has('name')) {
            $data = $request->only([
                'name',
                'number',
                'email',
                'country',
                'state',
                'city',
                'client-message',
                'date',
                'education',
                'experience',
                'certification',
                'dob',
                'gender',
                'Other_Certificate',
                'address',
                'package'
            ]);
            $data['message'] = $data['client-message'] ?? '';
            unset($data['client-message']);
            $data['created_date'] = $data['date'] ?? now();
            $data['updated_date'] = $data['date'] ?? now();
            unset($data['date']);

            if ($request->hasFile('profileImage')) {
                $file = $request->file('profileImage');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $data['profile_image'] = 'uploads/' . $filename;
            }

            if ($request->hasFile('trainerDocumnt')) {
                $file = $request->file('trainerDocumnt');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('trainerDoc'), $filename);
                $data['doc'] = 'trainerDoc/' . $filename;
            }

            if ($request->hasFile('trainerCv')) {
                $file = $request->file('trainerCv');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('trainerCv'), $filename);
                $data['cv_filecfdb7_file'] = 'trainerCv/' . $filename;
            }

            $id = $request->trainerId;
            $success = $id
                ? DB::table('trainer')->where('id', $id)->update($data)
                : DB::table('trainer')->insert($data);

            return response()->json([
                'success' => $success ? 1 : 0,
                'message' => $success ? 'Inserted Successfully' : 'Some error occurred!'
            ]);
        }

        return response()->json(['success' => 0, 'message' => 'Some error occurred!']);
    }

    public function changeStatus(Request $request)
    {
        $status = $request->status == 0 ? 1 : 0;
        $success = DB::table('trainer')->where('id', $request->id)->update(['is_trainer' => $status]);

        return response()->json([
            'success' => $success ? 1 : 0,
            'message' => $success ? 'Status Changed Successfully' : 'No data found!'
        ]);
    }

    public function deleteData(Request $request)
    {
        $success = DB::table('trainer')->where('id', $request->id)->update(['status_trainer' => 0]);

        return response()->json([
            'success' => $success ? 1 : 0,
            'message' => $success ? 'Deleted Successfully' : 'No data found!'
        ]);
    }

    public function getTrainer(Request $request)
    {
        if ($request->trainers == 'trainers') {
            $query = DB::table('trainer')
                ->where('is_trainer', 1)
                ->where('status_trainer', 1);

            if ($request->startDate) {
                $query->whereDate('created_date', '>=', $request->startDate);
            }

            if ($request->endDate) {
                $query->whereDate('created_date', '<=', $request->endDate);
            }

            $data = $query->orderByDesc('id')->get();

            return response()->json([
                'success' => $data->count() > 0 ? 1 : 0,
                'data' => $data,
                'message' => $data->count() > 0 ? '' : 'No data found!'
            ]);
        }
    }

    public function viewTrainerbyId(Request $request)
    {
        if ($request->id) {
            $trainer = DB::table('trainer')->where('id', $request->id)->first();
            return response()->json(['success' => 1, 'data' => $trainer]);
        }
    }

    public function changeReadStatus(Request $request)
    {
        if ($request->id) {
            $updated = DB::table('trainer')->where('id', $request->id)->update(['read_status' => 1]);
            return response()->json([
                'success' => $updated ? 1 : 0,
                'message' => $updated ? 'Updated Successfully' : 'Some error occurred!'
            ]);
        }

        // return view('leads');
    }

    public function showTrainer(Request $request)
    {
        if ($request->id) {
            $status = $request->status == 0 ? 1 : 0;
            $updated = DB::table('trainer')->where('id', $request->id)->update(['show_trainer' => $status]);

            return response()->json([
                'success' => $updated ? 1 : 0,
                'message' => $updated ? 'Updated Successfully' : 'Some error occurred!'
            ]);
        }

        // return view('leads');
    }

    public function isFeaturedTrainer(Request $request)
    {
        if ($request->id) {
            $status = $request->status == 0 ? 1 : 0;
            $updated = DB::table('trainer')->where('id', $request->id)->update(['is_featured_trainer' => $status]);

            return response()->json([
                'success' => $updated ? 1 : 0,
                'message' => $updated ? 'Updated Successfully' : 'Some error occurred!'
            ]);
        }

        // return view('leads');
    }
}
