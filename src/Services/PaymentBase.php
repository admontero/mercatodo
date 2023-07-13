<?php

namespace Services;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use Domain\Order\Models\Order;
use Illuminate\Http\JsonResponse;

abstract class PaymentBase
{
    abstract public function pay(StoreOrderRequest $request, Order $order): JsonResponse;
}
