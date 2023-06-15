<?php

namespace Tests\Unit\Listeners;

use Domain\Order\Events\OrderCanceled;
use Domain\Order\Events\OrderCreated;
use Domain\Order\Listeners\RestoreProductStock;
use Domain\Order\Listeners\SubtractProductStock;
use Domain\Order\Models\Order;
use Domain\Order\States\Pending;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestoreProductStockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function restore_product_stock_when_order_is_canceled(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();
        $product1 = Product::factory()->create(['id' => 1, 'name' => 'Balon', 'code' => '12345678', 'price' => '100000.00', 'stock' => 40]);
        $product2 = Product::factory()->create(['id' => 2, 'name' => 'Celular', 'code' => '87654321', 'price' => '700000.00', 'stock' => 21]);

        Order::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $customer->id],
            ))
            ->create([
                'state' => Pending::class,
            ])
            ->each(function ($order) use ($product1, $product2) {
                $order->products()->attach([
                    $product1->id => [
                        'price' => $product1->price,
                        'quantity' => 1,
                        'order_id' => $order->id,
                        'product_id' => $product1->id,
                    ],
                    $product2->id => [
                        'price' => $product2->price,
                        'quantity' => 1,
                        'order_id' => $order->id,
                        'product_id' => $product2->id,
                    ],
                ]);

                $order->update([
                    'total' => $order->products->map(function ($p) {
                        return $p->pivot->price * $p->pivot->quantity;
                    })->sum(),
                ]);
            });

        $order = Order::first();

        $listener = new SubtractProductStock();
        $event = new OrderCreated($order);

        $listener->handle($event);

        $this->assertEquals(39, $product1->fresh()->stock);
        $this->assertEquals(20, $product2->fresh()->stock);

        $listener2 = new RestoreProductStock();
        $event2 = new OrderCanceled($order);

        $listener2->handle($event2);

        $this->assertEquals(40, $product1->fresh()->stock);
        $this->assertEquals(21, $product2->fresh()->stock);
    }
}
