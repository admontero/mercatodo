<?php

namespace Domain\Order\Services;

use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;

class OrderService
{
    public function createOrder(OrderDTO $dto): Order
    {
        $order = Order::create([
            'code' => $this->generateOrderCode(),
            'total' => $dto->total,
            'provider' => $dto->provider,
            'user_id' => auth()->id(),
        ]);

        foreach (json_decode($dto->products, true) as $item) {
            $order->products()->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]);
        }

        return $order;
    }

    public function updateOrder(Order $order): Order
    {
        $order->save();

        return $order;
    }

    public function updateToPending(Order $order): Order
    {
        $order->state->transitionTo(Pending::class);

        return $order;
    }

    public function updateToCompleted(Order $order): Order
    {
        $order->state->transitionTo(Completed::class);

        return $order;
    }

    public function updateToCanceled(Order $order): Order
    {
        $order->state->transitionTo(Canceled::class);

        return $order;
    }

    public function getOrderByCode(string $code): ?Order
    {
        return Order::query()
            ->where('user_id', auth()->id())
            ->where('code', $code)
            ->first();
    }

    private function generateOrderCode(): string
    {
        do {
            $code = time() . random_int(1000, 9999);
        } while (Order::where('code', $code)->first());

        return $code;
    }
}
