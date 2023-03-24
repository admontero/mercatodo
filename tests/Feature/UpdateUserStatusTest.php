<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateUserStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_user_can_inactive_a_customer(): void
    {
        $this->seed(RoleSeeder::class);

        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->activated()->create();

        Passport::actingAs($admin);

        $this->postJson("/api/customers/{$customer->id}/status");

        $customer->refresh();

        $this->assertEquals('inactivated', $customer->status);
    }

    /**
     * @test
     */
    public function admin_user_can_active_a_customer(): void
    {
        $this->seed(RoleSeeder::class);

        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->inactivated()->create();

        Passport::actingAs($admin);

        $this->postJson("/api/customers/{$customer->id}/status");

        $customer->refresh();

        $this->assertEquals('activated', $customer->status);
    }

    /**
     * @test
     */
    public function guest_user_can_not_update_a_customer_status(): void
    {
        $this->seed(RoleSeeder::class);

        $customer = User::factory()->activated()->create();

        $response = $this->postJson("/api/customers/{$customer->id}/status");

        $response->assertStatus(401);

        $this->assertEquals('activated', $customer->status);
    }

    /**
     * @test
     */
    public function customer_user_can_not_update_a_customer_status(): void
    {
        $this->seed(RoleSeeder::class);

        $customer = User::factory()->activated()->create();

        Passport::actingAs($customer);

        $response = $this->postJson("/api/customers/{$customer->id}/status");

        $response->assertStatus(403);

        $this->assertEquals('activated', $customer->status);
    }
}
