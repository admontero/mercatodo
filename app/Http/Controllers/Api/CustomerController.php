<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(): CustomerCollection
    {
        $this->authorize('view-any', new Customer());

        $customers = new CustomerCollection(
            Customer::with('user')
                    ->select(['id', 'first_name', 'last_name', 'user_id'])
                    ->latest()
                    ->paginate(10)
        );

        return $customers;
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $this->authorize('update', $customer);

        $dataVal = $request->validated();

        $customer->update($dataVal);

        return response()->json(new CustomerResource($customer), 201);
    }
}
