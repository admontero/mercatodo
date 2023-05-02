<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShowCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function customer_can_get_itself_from_customer_route(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.customer.customers.show', $customer));

        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson([
                'email' => $customer->email
            ]);
    }

    /**
     * @test
     */
    public function customer_can_not_get_a_different_customer_from_customer_route(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $customer2 = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.customer.customers.show', $customer2));

        $response->assertStatus(403);
    }
}
