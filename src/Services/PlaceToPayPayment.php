<?php

namespace Services;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use Carbon\Carbon;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;
use Domain\Order\Services\OrderService;
use Domain\Order\States\Incompleted;
use Domain\Order\States\Pending;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlaceToPayPayment extends PaymentBase
{
    public function pay(StoreOrderRequest $request): JsonResponse
    {
        Log::info('[PAY]: Pago con PlaceToPay');

        $order = (new OrderService())->createOrder(OrderDTO::fromStoreRequest($request));

        try {
            $result = Http::post(
                config('placetopay.url').'/api/session',
                $this->createRequest($order, $request->ip(), $request->userAgent())
            );

            $order->request_id = $result->json()['requestId'];
            $order->url = $result->json()['processUrl'];

            $order = (new OrderService())->updateOrder($order);

            $order = (new OrderService())->updateToPending($order);

            $order->refresh();

            return response()->json(['url' => $order->url], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function retryPay(Request $request, Order $order): JsonResponse
    {
        Log::info('[PAY]: Reintento de Pago con PlaceToPay');

        if ($order->state->getValue() !== Incompleted::class) {
            return response()->json(['message' => 'No se puede reintentar el pago de esta order'], 422);
        }

        try {
            $result = Http::post(
                config('placetopay.url').'/api/session',
                $this->createRequest($order, $request->ip(), $request->userAgent())
            );

            $order->request_id = $result->json()['requestId'];
            $order->url = $result->json()['processUrl'];

            $order = (new OrderService())->updateOrder($order);

            $order = (new OrderService())->updateToPending($order);

            $order->refresh();

            return response()->json(['url' => $order->url], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function sendNotification(): void
    {
        Log::info('[PAY]: Enviamos la notificacion PlaceToPay');
    }

    public function getRequestInformation(string $code): View
    {
        $order = (new OrderService())->getOrderByCode($code);

        if (!$order) {
            return view('payment.error');
        }

        if ($order->state->getValue() !== Pending::class) {
            return view('payment.success', compact('order'));
        }

        try {
            $result = Http::post(config('placetopay.url') . '/api/session/' . $order->request_id, [
                'auth' => $this->getAuth(),
            ]);

            $status = $result->json()['status']['status'];

            match($status) {
                'APPROVED' => (new OrderService())->updateToCompleted($order),
                'REJECTED' => (new OrderService())->updateToCanceled($order),
                default => $order,
            };

            return view('payment.success', compact('order'));
        } catch (\Exception $e) {
            return view('payment.error', compact('order'));
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce.$seed.config('placetopay.tranKey'),
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function createRequest(Order $order, ?string $ipAddress, ?string $userAgent): array
    {
        return [
            'auth' => $this->getAuth(),
            'payer' => auth()->user()?->profileable?->document ? $this->getCustomerData() : null,
            'buyer' => $this->getCustomerData(),
            'payment' => [
                'reference' => $order->id,
                'description' => '',
                'amount' => [
                    'currency' => 'COP',
                    'total' => $order->total,
                ],
                'allowPartial' => false,
                'shipping' => $this->getCustomerData(),
                'items' => $this->getOrderItems($order),
            ],
            'expiration' => Carbon::now()->addHour(),
            'returnUrl' => route('orders.paymentReturn', $order->code),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
            'skipResult' => false,
            'noBuyerFill' => false,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getCustomerData(): array
    {
        return [
            'document' => auth()->user()?->profileable?->document,
                'documentType' => auth()->user()?->profileable?->document_type,
                'name' => auth()->user()?->profileable?->first_name,
                'surname' => auth()->user()?->profileable?->last_name,
                'email' => auth()->user()?->email,
                'mobile' => auth()->user()?->profileable?->cell_phone,
                'address' => [
                    'street' => auth()->user()?->profileable?->address,
                    'city' => auth()->user()?->profileable?->city?->name,
                    'state' => auth()->user()?->profileable?->state?->name,
                    'country' => auth()->user()?->profileable?->country?->name,
                    'phone' => auth()->user()?->profileable?->phone,
                ],
            ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getOrderItems(Order $order): array
    {
        return $order->products->map(function ($p) {
            return [
                'sku' => $p->stock,
                'name' => $p->name,
                'category' => $p->category?->name,
                'qty' => $p->getRelationValue('pivot')->quantity,
                'price' => $p->getRelationValue('pivot')->price,
                'tax' => 0,
            ];
        })->toArray();
    }
}
