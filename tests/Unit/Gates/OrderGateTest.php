<?php

namespace Tests\Unit\Gates;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class OrderGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function access_to_order_list(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertFalse(Gate::check('access-order-list'));

        Passport::actingAs($customer);

        $this->assertTrue(Gate::check('access-order-list'));
    }
}
