<?php

namespace App\Http\Controllers\Api\Customer;

use App\DataTransferObjects\CustomerProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProfileController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateCustomerProfileRequest $request, User $user): JsonResponse
    {
        $this->authorize('update-customer-profile', $user);

        $user = $this->userService->updateCustomerProfile(
            CustomerProfileDTO::fromUpdateProfileRequest($request),
            $user,
        );

        return response()->json(new CustomerResource($user), 201);
    }
}
