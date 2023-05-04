<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateCustomerProfileRequest $request, User $user): JsonResponse
    {
        $this->authorize('update-customer-profile', $user);

        $dataVal = $request->validated();

        $user->profileable()->update([
            'first_name' => $dataVal['first_name'],
            'last_name' => $dataVal['last_name'],
            'document_type' => $dataVal['document_type'] ?? null,
            'document' => $dataVal['document'] ?? null,
            'address' => $dataVal['address'] ?? null,
            'phone' => $dataVal['phone'] ?? null,
            'cell_phone' => $dataVal['cell_phone'] ?? null,
        ]);

        return response()->json(new CustomerResource($user->refresh()), 201);
    }
}
