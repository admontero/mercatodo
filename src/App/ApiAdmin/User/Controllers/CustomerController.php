<?php

namespace App\ApiAdmin\User\Controllers;

use App\ApiAdmin\User\Requests\UpdateCustomerRequest;
use App\ApiAdmin\User\Resources\CustomerCollection;
use App\ApiAdmin\User\Resources\CustomerResource;
use App\Controller;
use Domain\CustomerProfile\DTOs\CustomerProfileDTO;
use Domain\User\Models\User;
use Domain\User\Services\UserService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): CustomerCollection
    {
        $this->authorize('view-any-customer', new User());

        $customers = new CustomerCollection(
            User::customer()
                    ->latest()
                    ->paginate(10)
        );

        return $customers;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view-customer', $user);

        return response()->json(new CustomerResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, User $user): JsonResponse
    {
        $this->authorize('update-customer', $user);

        $user = $this->userService->updateCustomerProfile(
            CustomerProfileDTO::fromUpdateCustomerRequest($request),
            $user,
        );

        return response()->json(new CustomerResource($user), 201);
    }
}
