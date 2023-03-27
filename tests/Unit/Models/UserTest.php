<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_customer_status_can_be_changed(): void
    {
        $this->seed(RoleSeeder::class);

        $customer = User::factory()->create();

        $this->assertEquals('activated', (string) $customer->status);

        $customer->changeStatus();

        $this->assertEquals('inactivated', (string) $customer->status);

        $customer->changeStatus();

        $this->assertEquals('activated', (string) $customer->status);
    }

    /**
     * @test
     */
    public function customer_scope(): void
    {
        $this->seed(RoleSeeder::class);

        User::factory(20)->create();

        User::factory(5)->admin()->create();

        $users = User::all();

        $this->assertCount(25, $users);

        $customers = User::customer()->get();

        $this->assertCount(20, $customers);
    }
}
