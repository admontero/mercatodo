<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show(Request $request, User $user)
    {
        return response()->json(new CustomerResource($user), 200);
    }
}
