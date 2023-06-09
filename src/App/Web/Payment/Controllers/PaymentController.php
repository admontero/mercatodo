<?php

namespace App\Web\Payment\Controllers;

use App\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Services\PlaceToPayPayment;

class PaymentController extends Controller
{
    public function paymentReturn(PlaceToPayPayment $placeToPayPayment, string $code): View
    {
        return $placeToPayPayment->getRequestInformation($code);
    }
}
