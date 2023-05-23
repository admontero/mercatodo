<?php

namespace Tests\Unit\Models;

use Domain\Order\Models\Order;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_belongs_to_a_product(): void
    {
        $order_product = OrderProduct::factory()->create();

        $this->assertInstanceOf(Product::class, $order_product->product);
    }

    /**
     * @test
     */
    public function it_belongs_to_a_order(): void
    {
        $order_product = OrderProduct::factory()->create();

        $this->assertInstanceOf(Order::class, $order_product->order);
    }
}
