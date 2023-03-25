<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new User);

        $customers = new UserCollection(User::customer()->latest()->paginate(10));

        return $customers;
    }

    public function update(UpdateUserRequest $request, User $customer)
    {
        $this->authorize('update', $customer);

        $dataVal = $request->validated();

        $customer->update($dataVal);

        return response()->json(new UserResource($customer), 201);
    }
}
