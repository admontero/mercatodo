<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index()
    {
        if (! Gate::allows('view-customer')) {
            abort(403);
        }

        return view('backoffice.customers.index');
    }

    public function edit(User $customer)
    {
        if (! Gate::allows('update-customer', $customer)) {
            abort(403);
        }

        return view('backoffice.customers.edit', compact('customer'));
    }
}
