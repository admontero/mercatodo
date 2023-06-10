<?php

namespace Tests\Unit\Policies;

use Domain\Order\Policies\OrderPolicy;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    /**
     * @test
     */
    public function a_order_cannot_be_viewed_by_any_admin(): void
    {
        Passport::actingAs($this->admin);

        $policy = new OrderPolicy();

        $canViewAny = $policy->viewAny($this->admin);

        $this->assertFalse($canViewAny);
    }

    /**
     * @test
     */
    public function a_order_cannot_be_created_by_an_admin(): void
    {
        Passport::actingAs($this->admin);

        $policy = new OrderPolicy();

        $canCreateOrder = $policy->createOrder($this->admin);

        $this->assertFalse($canCreateOrder);
    }
}
