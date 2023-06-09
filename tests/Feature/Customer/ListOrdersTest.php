<?php

namespace Tests\Feature\Customer;

use Domain\Order\Models\Order;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListOrdersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function customer_can_get_his_orders_paginated(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Order::factory()->create(['user_id' => $customer->id, 'created_at' => now()->subDays(4)]);
        Order::factory()->create(['user_id' => $customer->id, 'created_at' => now()->subDays(3)]);
        Order::factory()->create(['user_id' => $customer->id, 'created_at' => now()->subDays(2)]);
        Order::factory()->create(['user_id' => $customer->id, 'created_at' => now()->subDays(1)]);
        $order5 = Order::factory()->create(['user_id' => $customer->id]);

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.customer.orders.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 5],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['code'], $order5->code);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_orders(): void
    {
        Order::factory(10)->create();

        $response = $this->getJson(route('api.customer.orders.index'));

        $response->assertStatus(401);
    }
}
