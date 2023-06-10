<?php

namespace App\Web\Order\Controllers;

use App\Controller;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('access-order-list');

        return view('orders.index');
    }
}
