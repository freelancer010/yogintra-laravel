<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommonController extends Controller
{
    protected $apiUrl = 'https://geodata.phplift.net/api/index.php';

    public function getCountries()
    {
        $response = Http::post($this->apiUrl, [
            'type' => 'getCountries',
        ]);

        return response()->json($response->json());
    }

    public function getStates(Request $request)
    {
        $countryId = $request->input('countryId');

        $response = Http::post($this->apiUrl, [
            'type' => 'getStates',
            'countryId' => $countryId,
        ]);

        return response()->json($response->json());
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('stateId');

        $response = Http::post($this->apiUrl, [
            'type' => 'getCities',
            'countryId' => '', // it doesn't seem to require this, per original JS
            'stateId' => $stateId,
        ]);

        return response()->json($response->json());
    }
}
