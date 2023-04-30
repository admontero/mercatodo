<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        if (! Gate::allows('view-customer')) {
            abort(403);
        }

        return view('backoffice.customers.index');
    }

    public function edit(User $customer): View
    {
        if (! Gate::allows('update-customer', $customer)) {
            abort(403);
        }

        return view('backoffice.customers.edit', compact('customer'));
    }
}
