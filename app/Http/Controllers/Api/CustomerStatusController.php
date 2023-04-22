<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Customer $customer): JsonResponse
    {
        $this->authorize('update-status', $customer);

        $customer->user->changeStatus();

        return response()->json(['status' => (string) $customer->user->status]);
    }
}
