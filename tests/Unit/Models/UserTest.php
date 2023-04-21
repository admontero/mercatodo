<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_has_one_customer(): void
    {
        $user = User::factory()
                    ->has(Customer::factory()->count(1))
                    ->create();

        $this->assertInstanceOf(Customer::class, $user->customer);
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
        User::factory(20)->create();

        User::factory(5)->admin()->create();

        $users = User::all();

        $this->assertCount(25, $users);

        $customers = User::customers()->get();

        $this->assertCount(20, $customers);
    }
}
