<?php

namespace Tests\Unit\Gates;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function view_admin_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertTrue(Gate::check('access-admin-dashboard'));

        Passport::actingAs($customer);

        $this->assertFalse(Gate::check('access-admin-dashboard'));
    }

    /**
     * @test
     */
    public function view_customer_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertTrue(Gate::check('access-customer-list'));

        Passport::actingAs($customer);

        $this->assertFalse(Gate::check('access-customer-list'));
    }

    /**
     * @test
     */
    public function update_customer_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();
        $model = User::factory()->customer()->create();

        Passport::actingAs($admin);

        $this->assertTrue(Gate::check('access-customer-edit', [$model]));

        Passport::actingAs($customer);

        $this->assertFalse(Gate::check('access-customer-edit', [$model]));
    }

    /**
     * @test
     */
    public function update_profile_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertFalse(Gate::check('access-profile-edit'));

        Passport::actingAs($customer);

        $this->assertTrue(Gate::check('access-profile-edit'));
    }
}
