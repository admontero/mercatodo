<?php

namespace Tests\Unit\Models;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function route_key_name_is_set_to_slug(): void
    {
        $category = Category::factory()->create();

        $this->assertEquals('slug', $category->getRouteKeyName(), 'The route key name must be slug');
    }

    /**
     * @test
     */
    public function a_category_has_many_products(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Product::class, $category->products()->first());
    }
}
