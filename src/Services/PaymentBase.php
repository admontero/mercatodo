<?php

namespace Services;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use Domain\Order\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class PaymentBase
{
    abstract public function pay(StoreOrderRequest $request): JsonResponse;

    abstract public function sendNotification(): void;
}
