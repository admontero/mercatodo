<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateProductStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_user_can_inactive_a_product(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.products.update-status', $product));

        $product->refresh();

        $this->assertEquals('inactivated', $product->status);
    }

    /**
     * @test
     */
    public function admin_user_can_active_a_product(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $product = Product::factory()->inactivated()->create();

        Passport::actingAs($admin);

        $this->postJson(route('api.products.update-status', $product));

        $product->refresh();

        $this->assertEquals('activated', $product->status);
    }

    /**
     * @test
     */
    public function guest_user_can_not_update_a_product_status(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('api.products.update-status', $product));

        $response->assertStatus(401);

        $this->assertEquals('activated', $product->status);
    }

    /**
     * @test
     */
    public function customer_user_can_not_update_a_product_status(): void
    {
        $customer = Customer::factory()->create();

        $product = Product::factory()->create();

        Passport::actingAs($customer->user);

        $response = $this->postJson(route('api.products.update-status', $product));

        $response->assertStatus(403);

        $this->assertEquals('activated', $product->status);
    }
}
