<?php

namespace Tests\Unit\Http\Resources;

use App\ApiCustomer\Order\Resources\OrderResource;
use Carbon\Carbon;
use Domain\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_order_resource_must_have_the_required_fields(): void
    {
        $order = Order::factory()->create();

        $orderResource = OrderResource::make($order)->resolve();

        $this->assertEquals($order->id, $orderResource['id']);
        $this->assertEquals($order->code, $orderResource['code']);
        $this->assertEquals($order->total, $orderResource['total']);
        $this->assertEquals($order->provider, $orderResource['provider']);
        $this->assertEquals($order->request_id, $orderResource['request_id']);
        $this->assertEquals($order->url, $orderResource['url']);
        $this->assertEquals($order->state, $orderResource['state']);
        $this->assertEquals(Carbon::parse($order->created_at)->isoFormat('D \d\e MMMM \d\e YYYY'), $orderResource['date']);
    }
}
