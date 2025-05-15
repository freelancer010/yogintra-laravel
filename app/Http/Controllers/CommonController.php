<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function getCountries()
    {
        // Fetch countries from the database
        $countries = DB::table('countries')->get();

        return response()->json(['result' => $countries]);
    }

    public function getStates(Request $request)
    {
        $countryId = $request->input('countryId');

        // Fetch states based on the country ID
        $states = DB::table('states')->where('country_id', $countryId)->get();

        return response()->json(['result' => $states]);
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('stateId');

        // Fetch cities based on the state ID
        $cities = DB::table('cities')->where('state_id', $stateId)->get();

        return response()->json(['result' => $cities]);
    }
}
