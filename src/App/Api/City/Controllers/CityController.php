<?php

namespace App\Api\City\Controllers;

use App\Controller;
use Domain\City\Services\CityService;
use Domain\State\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(
        protected CityService $service
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, State $state): JsonResponse
    {
        $cities = $this->service->getCities($state);

        return response()->json($cities);
    }
}
