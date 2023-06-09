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
        return view('orders.index');
    }
}
