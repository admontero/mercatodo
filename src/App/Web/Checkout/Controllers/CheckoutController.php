<?php

namespace App\Web\Checkout\Controllers;

use App\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View
    {
        return view('checkout.index', [
            'processors' => [
                'PlaceToPay',
            ],
        ]);
    }
}
