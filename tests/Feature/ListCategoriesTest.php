<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_all_categories(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();
        Category::factory()->create(['created_at' => now()->subDays(2)]);
        Category::factory()->create(['created_at' => now()->subDays(1)]);
        $category3 = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/categories');

        $response->assertOk();

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data', 'links',
        ]);

        $this->assertEquals($response['data'][0]['name'], $category3->name);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_all_categories(): void
    {
        $this->seed(RoleSeeder::class);

        User::factory(10)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_all_categories(): void
    {
        $this->seed(RoleSeeder::class);

        $customer = User::factory()->create();

        User::factory(10)->create();

        Passport::actingAs($customer);

        $response = $this->actingAs($customer)
                                    ->getJson('/api/categories');

        $response->assertStatus(403);
    }
}
