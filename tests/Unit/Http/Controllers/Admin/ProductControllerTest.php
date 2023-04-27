<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_method_must_returns_the_post_index_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.products.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.products.index');
    }

    /**
     * @test
     */
    public function create_method_must_returns_the_post_create_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.products.create'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.products.create');
    }

    /**
     * @test
     */
    public function edit_method_must_returns_the_product_edit_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.products.edit', $product));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.products.edit')
            ->assertViewHas('product');
    }
}
