<?php

namespace Domain\Order\Services;

use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Product\Services\ProductService;

class OrderService
{
    public function createOrder(OrderDTO $dto): Order
    {
        $order = Order::create([
            'code' => $this->generateOrderCode(),
            'provider' => $dto->provider,
            'user_id' => auth()->id(),
        ]);

        foreach ($dto->products as $item) {
            (new ProductService())->checkStockAvailable($item['id'], $item['quantity']);

            $product = (new ProductService())->getProductById($item['id']);

            $order->products()->attach($item['id'], ['price' => $product->price, 'quantity' => $item['quantity']]);
        }

        $this->updateOrderTotal($order);

        return $order;
    }

    public function updateOrder(Order $order): Order
    {
        $order->save();

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

    public function updateOrderTotal(Order $order): void
    {
        $total = $order->products->map(function ($p) {
            return $p->pivot->price * $p->pivot->quantity;
        })->sum();

        $order->update([
            'total' => $total
        ]);
    }

    private function generateOrderCode(): string
    {
        do {
            $code = time() . random_int(1000, 9999);
        } while (Order::where('code', $code)->first());

        return $code;
    }
}
