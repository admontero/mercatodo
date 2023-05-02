<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_a_category_from_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.categories.show', $category));

        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson([
                'id' => $category->id,
                'name' => $category->name,
            ]);
    }

    /**
     * @test
     */
    public function guest_user_cannot_get_a_category_from_admin_route(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('api.admin.categories.show', $category));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_cannot_get_a_category_from_admin_route(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        $category = Category::factory()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.admin.categories.show', $category));

        $response->assertStatus(403);
    }
}
