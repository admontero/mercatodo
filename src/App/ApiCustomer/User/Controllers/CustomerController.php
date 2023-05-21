<?php

namespace App\ApiCustomer\User\Controllers;

use App\ApiAdmin\User\Resources\CustomerResource;
use App\Controller;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorize('view-customer', $user);

        return response()->json(new CustomerResource($user), 200);
    }
}
