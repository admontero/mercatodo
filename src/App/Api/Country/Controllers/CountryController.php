<?php

namespace App\Api\Country\Controllers;

use App\Controller;
use Domain\Country\Services\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(
        protected CountryService $service
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $countries = $this->service->getCountries();

        return response()->json($countries);
    }
}
