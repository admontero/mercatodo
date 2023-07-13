<?php

namespace Tests\Unit\Services;

use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_return_null_if_order_creation_fails(): void
    {
        $products = [
            [
                'id' => 1,
                'quantity' => -20,
            ],
        ];

        $order = (new OrderService())->createOrder(new OrderDTO('PlaceToPay', $products));

        $this->assertNull($order);
    }
}
