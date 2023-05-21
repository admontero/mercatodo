<?php

namespace Tests\Unit\Models;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function route_key_name_is_set_to_slug(): void
    {
        $product = Product::factory()->create();

        $this->assertEquals('slug', $product->getRouteKeyName(), 'The route key name must be slug');
    }

    /**
     * @test
     */
    public function a_product_belongs_to_a_category(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
    }

    /**
     * @test
     */
    public function active_scope(): void
    {
        Product::factory(10)->create();

        Product::factory(2)->inactivated()->create();

        $products = Product::all();

        $this->assertCount(12, $products);

        $products = Product::active()->get();

        $this->assertCount(10, $products);
    }
}
