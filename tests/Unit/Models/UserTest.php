<?php

namespace Tests\Unit\Models;

use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\Order\Models\Order;
use Domain\User\Models\User;
use Domain\User\States\Activated;
use Domain\User\States\Inactivated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_customer_has_one_customer_profile(): void
    {
        $user = User::factory()->create([
            'profileable_type' => CustomerProfile::class,
            'profileable_id' => CustomerProfile::factory()->create(),
        ]);

        $this->assertInstanceOf(CustomerProfile::class, $user->profileable);
    }

    /**
     * @test
     */
    public function a_user_has_many_orders(): void
    {
        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Order::class, $user->orders()->first());
    }

    /**
     * @test
     */
    public function a_user_status_can_be_changed(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('activated', (string) $user->state);

        $user->state->transitionTo(Inactivated::class);

        $this->assertEquals('inactivated', (string) $user->state);

        $user->state->transitionTo(Activated::class);

        $this->assertEquals('activated', (string) $user->state);
    }

    /**
     * @test
     */
    public function customer_scope(): void
    {
        User::factory(20)->customer()->create();

        User::factory(5)->admin()->create();

        $users = User::all();

        $this->assertCount(25, $users);

        $customers = User::customer()->get();

        $this->assertCount(20, $customers);
    }
}
