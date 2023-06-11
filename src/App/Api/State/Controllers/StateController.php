<?php

namespace App\Api\State\Controllers;

use App\Controller;
use Domain\Country\Models\Country;
use Domain\State\Services\StateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __construct(
        protected StateService $service
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Country $country): JsonResponse
    {
        $states = $this->service->getStates($country);

        return response()->json($states);
    }
}
