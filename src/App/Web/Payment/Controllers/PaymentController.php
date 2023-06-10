<?php

namespace App\Web\Payment\Controllers;

use App\Controller;
use Illuminate\View\View;
use Services\PlaceToPayPayment;

class PaymentController extends Controller
{
    public function paymentReturn(PlaceToPayPayment $placeToPayPayment, string $code): View
    {
        $this->authorize('access-payment-return');

        return $placeToPayPayment->getRequestInformation($code);
    }
}
