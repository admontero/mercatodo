<?php

namespace App\ApiCustomer\User\Controllers;

use App\ApiAdmin\User\Resources\CustomerResource;
use App\ApiCustomer\User\Requests\UpdateCustomerProfileRequest;
use App\Controller;
use Domain\CustomerProfile\DTOs\CustomerProfileDTO;
use Domain\User\Models\User;
use Domain\User\Services\UserService;
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
