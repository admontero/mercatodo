<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShowProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_a_product_from_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.products.show', $product));

        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson([
                'name' => $product->name,
                'code' => $product->code,
                'category' => [
                    'id' => $product->category_id
                ]
            ]);
    }

    /**
     * @test
     */
    public function guest_user_cannot_get_a_product_from_admin_route(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.admin.products.show', $product));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_cannot_get_a_product_from_admin_route(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        $product = Product::factory()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.admin.products.show', $product));

        $response->assertStatus(403);
    }
}
