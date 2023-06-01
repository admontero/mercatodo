<?php

namespace App\Api\City\Controllers;

use App\Controller;
use Domain\City\Models\City;
use Domain\State\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, State $state): JsonResponse
    {
        $cities = City::where('state_id', $state->id)->select('id', 'name')->get();

        return response()->json($cities);
    }
}
