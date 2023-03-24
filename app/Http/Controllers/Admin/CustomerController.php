<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('backoffice.customers.index');
    }

    public function edit(User $customer)
    {
        return view('backoffice.customers.edit', compact('customer'));
    }
}
