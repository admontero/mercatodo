<?php

namespace Tests\Feature\Admin;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShowCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_a_customer(): void
    {
        $admin = User::factory()->admin()->create();

        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.customers.show', $customer));

        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson([
                'email' => $customer->email,
            ]);
    }

    /**
     * @test
     */
    public function guest_user_cannot_get_a_customer(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $response = $this->getJson(route('api.admin.customers.show', $customer));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_a_customer_in_admin_route(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $customer2 = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.admin.customers.show', $customer2));

        $response->assertStatus(403);
    }
}
