<?php

namespace Tests\Feature\Customer;

use Domain\Order\Events\OrderCanceled;
use Domain\Order\Events\OrderCreated;
use Domain\Order\Listeners\RestoreProductStock;
use Domain\Order\Listeners\SubtractProductStock;
use Domain\Order\Models\Order;
use Domain\Order\Services\OrderService;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();
    }

    /** @test */
    public function it_throws_an_exception_if_order_is_not_created(): void
    {
        Product::factory()->create(['id' => 1, 'name' => 'Balon', 'code' => '12345678', 'price' => '100000.00', 'stock' => 40]);
        Product::factory()->create(['id' => 2, 'name' => 'Celular', 'code' => '87654321', 'price' => '700000.00', 'stock' => 21]);

        Passport::actingAs($this->customer);

        $data = $this->getOrderValidData();

        $serviceMock = Mockery::mock(OrderService::class);
        $serviceMock->shouldReceive('createOrder')
                    ->once()
                    ->andReturn(null);

        $this->app->instance(OrderService::class, $serviceMock);

        $this->postJson(route('api.customer.payments.processPayment'), $data)
            ->assertStatus(500)
            ->assertJsonStructure(['message'])
            ->assertJsonFragment(['message' => 'Hubo un error al crear la orden']);

        $this->assertDatabaseEmpty('orders');
    }

    /** @test */
    public function customer_can_pay_a_order(): void
    {
        Event::fake([
            OrderCreated::class,
        ]);

        Product::factory()->create(['id' => 1, 'name' => 'Balon', 'code' => '12345678', 'price' => '100000.00', 'stock' => 40]);
        Product::factory()->create(['id' => 2, 'name' => 'Celular', 'code' => '87654321', 'price' => '700000.00', 'stock' => 21]);

        Passport::actingAs($this->customer);

        $mockResponse = [
            'status' => [
                'status' => 'OK',
                'reason' => 'PC',
                'message' => 'La petición se ha procesado correctamente',
                'date' => '2021-11-30T15:08:27-05:00',
            ],
            'requestId' =>  1,
            'processUrl' => 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mockResponse)]);

        $data = $this->getOrderValidData();

        $this->postJson(route('api.customer.payments.processPayment'), $data)
            ->assertSuccessful()
            ->assertJsonStructure(['url']);

        $this->assertDatabaseHas('orders', [
            'total' => '800000',
            'provider' => 'PlaceToPay',
        ]);

        $order = Order::first();

        Event::assertDispatched(function (OrderCreated $event) use ($order) {
            return $event->order->id === $order->id;
        });

        Event::assertListening(
            OrderCreated::class,
            SubtractProductStock::class
        );
    }

    /** @test */
    public function it_returns_an_error_message_if_order_pay_fails(): void
    {
        Product::factory()->create(['id' => 1, 'name' => 'Balon', 'code' => '12345678', 'price' => '100000.00', 'stock' => 40]);
        Product::factory()->create(['id' => 2, 'name' => 'Celular', 'code' => '87654321', 'price' => '700000.00', 'stock' => 21]);

        Passport::actingAs($this->customer);

        $data = $this->getOrderValidData();

        Http::fake([config('placetopay.url').'/*' => Http::response([], 500)]);

        $this->postJson(route('api.customer.payments.processPayment'), $data)
            ->assertStatus(500)
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function a_order_can_be_completed(): void
    {
        $this->withoutVite();

        $order = $this->getNewOrder();

        Passport::actingAs($this->customer);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2022-07-27T14:51:27-05:00',
            ],
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mockResponse)]);

        $this->get(route('orders.paymentReturn', $order))
            ->assertSuccessful()
            ->assertViewIs('payment.success')
            ->assertViewHas('order');

        $order->refresh();

        $this->assertEquals(Completed::class, $order->state->getValue());
    }

    /** @test */
    public function a_order_can_be_canceled(): void
    {
        $this->withoutVite();

        Event::fake([
            OrderCanceled::class,
        ]);

        $order = $this->getNewOrder();

        Passport::actingAs($this->customer);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'REJECTED',
                'reason' => 'XN',
                'message' => 'Se ha rechazado la petición',
                'date' => '2021-11-30T16:44:24-05:00',
            ],
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mockResponse)]);

        $this->get(route('orders.paymentReturn', $order))
            ->assertSuccessful()
            ->assertViewIs('payment.success')
            ->assertViewHas('order');

        $order->refresh();

        $this->assertEquals(Canceled::class, $order->state->getValue());

        Event::assertDispatched(function (OrderCanceled $event) use ($order) {
            return $event->order->id === $order->id;
        });

        Event::assertListening(
            OrderCanceled::class,
            RestoreProductStock::class
        );
    }

    /** @test */
    public function a_order_may_still_be_pending(): void
    {
        $this->withoutVite();

        $order = $this->getNewOrder();

        Passport::actingAs($this->customer);

        $mockResponse = [
            'requestId' => 1,
            'status' => [
                'status' => 'PENDING',
                'reason' => 'PT',
                'message' => 'La petición se encuentra pendiente',
                'date' => '2021-11-30T15:45:57-05:00',
            ],
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mockResponse)]);

        $this->get(route('orders.paymentReturn', $order))
            ->assertSuccessful()
            ->assertViewIs('payment.success')
            ->assertViewHas('order');

        $order->refresh();

        $this->assertEquals(Pending::class, $order->state->getValue());
    }

    /** @test */
    public function it_returns_the_current_order_state_if_is_not_pending(): void
    {
        $this->withoutVite();

        $order = $this->getNewOrder(Completed::class);

        Passport::actingAs($this->customer);

        $this->get(route('orders.paymentReturn', $order))
            ->assertSuccessful()
            ->assertViewIs('payment.success')
            ->assertViewHas('order');

        $this->assertEquals(Completed::class, $order->state->getValue());
    }

    /** @test */
    public function it_returns_payment_error_if_order_does_not_exists(): void
    {
        $this->withoutVite();

        Passport::actingAs($this->customer);

        $this->get('/orders/5235234/payment')
            ->assertViewIs('payment.error');
    }

    /** @test */
    public function it_returns_an_error_view_if_order_request_information_fails(): void
    {
        $this->withoutVite();

        Passport::actingAs($this->customer);

        $order = $this->getNewOrder();

        Http::fake([config('placetopay.url').'/*' => Http::response([], 500)]);

        $this->get(route('orders.paymentReturn', $order))
            ->assertViewIs('payment.error');
    }

    protected function getNewOrder(string $state = Pending::class): Order
    {
        Order::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $this->customer->id],
            ))
            ->create([
                'state' => $state,
            ])
            ->each(function ($order) {
                OrderProduct::factory()
                    ->count(random_int(1, 5))
                    ->state(new Sequence(function (Sequence $sequence) use ($order) {
                        $product = Product::factory()->create();

                        return [
                            'price' => $product->price,
                            'quantity' => random_int(1, 3),
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                        ];
                    }))
                    ->create();

                $order->update([
                    'total' => $order->products->map(function ($p) {
                        return $p->pivot->price * $p->pivot->quantity;
                    })->sum(),
                ]);
            });

        return Order::first();
    }

    protected function getOrderValidData(array $invalidData = []): array
    {
        $validData = [
            'provider' => 'PlaceToPay',
            'products' => json_encode(
                $this->getProductsArray()
            ),
        ];

        return array_merge($validData, $invalidData);
    }

    protected function getProductsArray(): array
    {
        return Product::with('category')->get()
            ->map(function ($p) {
                $p->quantity = 1;

                return $p;
            })
            ->toArray();
    }
}
