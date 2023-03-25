<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_all_customers(): void
    {
        $this->withoutExceptionHandling();

        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();
        $user1 = User::factory()->create(['created_at' => now()->subDays(4)]);
        $user2 = User::factory()->create(['created_at' => now()->subDays(3)]);
        $user3 = User::factory()->create(['created_at' => now()->subDays(2)]);
        $user4 = User::factory()->create(['created_at' => now()->subDays(1)]);
        $user5 = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/customers');

        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 5]
        ]);

        $response->assertJsonStructure([
            'data', 'links'
        ]);

        $this->assertEquals($response['data'][0]['email'], $user5->email);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_all_customers(): void
    {
        $this->seed(RoleSeeder::class);

        User::factory(10)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_all_customers(): void
    {
        $this->seed(RoleSeeder::class);

        $customer = User::factory()->create();

        User::factory(10)->create();

        Passport::actingAs($customer);

        $response = $this->actingAs($customer)
                                    ->getJson('/api/customers');

        $response->assertStatus(403);
    }
}
