<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateCustomerStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_user_can_inactive_a_customer(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $customer = Customer::factory()->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.customers.update-status', $customer));

        $this->assertEquals('inactivated', $customer->user->status);
    }

    /**
     * @test
     */
    public function admin_user_can_active_a_customer(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $customer = Customer::factory()->inactivated()->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.customers.update-status', $customer));

        $this->assertEquals('activated', $customer->user->status);
    }

    /**
     * @test
     */
    public function guest_user_can_not_update_a_customer_status(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->postJson(route('api.customers.update-status', $customer));

        $response->assertStatus(401);

        $this->assertEquals('activated', $customer->user->status);
    }

    /**
     * @test
     */
    public function customer_user_can_not_update_a_customer_status(): void
    {
        $customer = Customer::factory()->create();

        Passport::actingAs($customer->user);

        $response = $this->postJson(route('api.customers.update-status', $customer));

        $response->assertStatus(403);

        $this->assertEquals('activated', $customer->user->status);
    }
}
