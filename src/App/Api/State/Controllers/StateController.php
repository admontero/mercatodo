<?php

namespace App\Api\State\Controllers;

use App\Controller;
use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Country $country): JsonResponse
    {
        $states = State::where('country_id', $country->id)->select('id', 'name')->get();

        return response()->json($states);
    }
}
