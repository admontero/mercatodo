<?php

namespace App\Api\Country\Controllers;

use App\Controller;
use Domain\Country\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $countries = Country::select('id', 'name')->get();

        return response()->json($countries);
    }
}
