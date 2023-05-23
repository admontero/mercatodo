<?php

namespace Tests\Feature\Admin;

use Domain\User\Models\User;
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

        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.admin.customers.inactivate', $customer));

        $customer->refresh();

        $this->assertEquals('inactivated', $customer->state);
    }

    /**
     * @test
     */
    public function admin_user_can_active_a_customer(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $customer = User::factory()
            ->customer()
            ->inactivated()
            ->withCustomerProfile()
            ->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.admin.customers.activate', $customer));

        $customer->refresh();

        $this->assertEquals('activated', $customer->state);
    }

    /**
     * @test
     */
    public function guest_user_can_not_inactivate_a_customer_status(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        $response = $this->postJson(route('api.admin.customers.inactivate', $customer));

        $response->assertStatus(401);

        $customer->refresh();

        $this->assertEquals('activated', $customer->state);
    }

    /**
     * @test
     */
    public function customer_user_can_not_inactivate_a_customer_status(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Passport::actingAs($customer);

        $response = $this->postJson(route('api.admin.customers.inactivate', $customer));

        $response->assertStatus(403);

        $customer->refresh();

        $this->assertEquals('activated', $customer->state);
    }
}
