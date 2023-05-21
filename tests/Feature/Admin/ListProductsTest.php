<?php

namespace Tests\Feature\Admin;

use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_all_products_from_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create(['created_at' => now()->subDays(4)]);
        Product::factory()->create(['created_at' => now()->subDays(3)]);
        $product3 = Product::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.products.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 3],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['code'], $product3->code);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_all_products_from_admin_route(): void
    {
        Product::factory(10)->create();

        $response = $this->getJson(route('api.admin.products.index'));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_all_products_from_admin_route(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Product::factory(10)->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.admin.products.index'));

        $response->assertStatus(403);
    }
}
