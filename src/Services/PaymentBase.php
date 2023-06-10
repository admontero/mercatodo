<?php

namespace Services;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use Illuminate\Http\JsonResponse;

abstract class PaymentBase
{
    abstract public function pay(StoreOrderRequest $request): JsonResponse;
}
