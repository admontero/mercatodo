<?php

namespace Services;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;
use Carbon\Carbon;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Events\OrderCreated;
use Domain\Order\Models\Order;
use Domain\Order\Services\OrderService;
use Domain\Order\States\Pending;
use Domain\Order\Traits\OrderTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PlaceToPayPayment extends PaymentBase
{
    use OrderTrait;

    public function pay(StoreOrderRequest $request): JsonResponse
    {
        Log::channel('placetopay')->info('[PAY]: Pago con PlaceToPay');

        $order = (new OrderService())->createOrder(OrderDTO::fromStoreRequest($request));

        try {
            $result = Http::post(
                config('placetopay.url').'/api/session',
                $this->createRequest($order, $request->ip(), $request->userAgent())
            );

            if (!$result->ok()) {
                Log::channel('placetopay')->info('[PAY]: Error en la respuesta del servicio de PlaceToPay', [
                    'result' => $result
                ]);
                throw new \Exception('Hubo un error al crear el pago.', 500);
            }

            $order->request_id = $result->json()['requestId'];
            $order->url = $result->json()['processUrl'];

            $order = (new OrderService())->updateOrder($order);

            OrderCreated::dispatch($order);

            $this->sendNotification($order);

            return response()->json(['url' => $order->url], 200);
        } catch (\Exception $e) {
            (new OrderService())->deleteOrder($order);
            Log::channel('placetopay')->info('[PAY]: Error al generar la orden');
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function sendNotification(Order $order): void
    {
        Log::channel('placetopay')->info('[PAY]: Enviamos la notificacion PlaceToPay');
        (new OrderService())->sendOrderProcessedNotification($order);
    }

    public function getRequestInformation(string $code): View
    {
        Log::channel('placetopay')->info('[PAY]: Consultamos el estado del pago de la orden');

        $order = (new OrderService())->getOrderByCode($code);

        if (!$order) {
            Log::channel('placetopay')->info('[PAY]: Error, la orden no fue encontrada en el sistema');
            return view('payment.error');
        }

        if ($order->state->getValue() !== Pending::class) {
            Log::channel('placetopay')->info('[PAY]: Error, la orden ya ha actualizado su estado');
            return view('payment.success', compact('order'));
        }

        try {
            $result = Http::post(config('placetopay.url') . '/api/session/' . $order->request_id, [
                'auth' => $this->getAuth(),
            ]);

            if (!$result->ok()) {
                Log::channel('placetopay')->info('[PAY]: Error en la respuesta del servicio de PlaceToPay', [
                    'result' => $result
                ]);
                throw new \Exception('Hubo un error en la consulta del pago.', 500);
            }

            $status = $result->json()['status']['status'];

            match($status) {
                'APPROVED' => (new OrderService())->updateToCompleted($order),
                'REJECTED' => (new OrderService())->updateToCanceled($order),
                default => $order,
            };

            return view('payment.success', compact('order'));
        } catch (\Exception $e) {
            Log::channel('placetopay')->info('[PAY]: Error al consultar el pago de la orden');
            return view('payment.error', compact('order'));
        }
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
            'expiration' => Carbon::now()->addMinutes(15),
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
