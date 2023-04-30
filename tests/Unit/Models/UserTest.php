<?php

namespace Tests\Unit\Models;

use App\Models\CustomerProfile;
use App\Models\User;
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
    public function a_user_status_can_be_changed(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('activated', (string) $user->status);

        $user->changeStatus();

        $this->assertEquals('inactivated', (string) $user->status);

        $user->changeStatus();

        $this->assertEquals('activated', (string) $user->status);
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
