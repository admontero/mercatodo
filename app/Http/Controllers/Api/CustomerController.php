<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = UserResource::collection(
            User::whereRelation('roles', 'name', 'customer')->latest()->paginate(10)
        );

        return $customers;
    }

    public function update(Request $request, User $customer)
    {
        $dataVal = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:80'],
            'document_type' => ['nullable', 'string', 'required_with:document'],
            'document' => ['nullable', 'string', 'max:30', 'required_with:document_type'],
            'address' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cell_phone' => ['nullable', 'string', 'max:25'],
        ]);

        $customer->update($dataVal);

        return response()->json(new UserResource($customer), 201);
    }
}
