<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function show(User $user)
    {
        $this->authorize('view-customer', $user);

        return response()->json(new CustomerResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, User $user)
    {
        $this->authorize('update-customer', $user);

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

        return response()->json(new CustomerResource($user), 201);
    }
}
