<?php

namespace App\ApiCustomer\Payment\Controllers;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use App\Controller;
use Domain\Order\Models\Order;
use Domain\Shared\Contracts\PaymentFactoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processPayment(StoreOrderRequest $request, PaymentFactoryInterface $paymentFactory): JsonResponse
    {
        $this->authorize('create-order', new Order);

        $processor = $paymentFactory->initializePayment('PlaceToPay');
        return $processor->pay($request);
    }
}
