<?php

namespace App\WebAdmin\User\Controllers;

use App\Controller;
use Domain\User\Models\User;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $this->authorize('access-customer-list');

        return view('backoffice.customers.index');
    }

    public function edit(User $customer): View
    {
        $this->authorize('access-customer-edit', $customer);

        return view('backoffice.customers.edit', compact('customer'));
    }
}
