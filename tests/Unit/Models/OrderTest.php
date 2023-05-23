<?php

namespace Tests\Unit\Models;

use Domain\Order\Models\Order;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_order_belongs_to_a_user(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
    }

    /**
     * @test
     */
    public function it_belongs_to_many_products(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $product2 = Product::factory()->create();

        OrderProduct::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        OrderProduct::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
        ]);

        $this->assertInstanceOf(Product::class, $order->products->first());
    }
}
