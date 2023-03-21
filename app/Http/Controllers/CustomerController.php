<?php

namespace App\Http\Controllers;

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
}
