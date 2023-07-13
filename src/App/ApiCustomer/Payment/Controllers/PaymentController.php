<?php

namespace App\ApiCustomer\Payment\Controllers;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use App\Controller;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;
use Domain\Order\Services\OrderService;
use Domain\Shared\Contracts\PaymentFactoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
    }

    public function processPayment(StoreOrderRequest $request, PaymentFactoryInterface $paymentFactory): JsonResponse
    {
        $this->authorize('create-order', new Order());

        $order = $this->orderService->createOrder(OrderDTO::fromStoreRequest($request));

        if (! $order) {
            Log::channel('placetopay')->info('[PAY]: Error, la orden no ha sido creada en el sistema');

            return response()->json(['message' => 'Hubo un error al crear la orden'], 500);
        }

        $processor = $paymentFactory->initializePayment('PlaceToPay');

        return $processor->pay($request, $order);
    }
}
