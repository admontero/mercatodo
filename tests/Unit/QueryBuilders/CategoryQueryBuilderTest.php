<?php

namespace Tests\Unit\QueryBuilders;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_is_filtering_by_category_id(): void
    {
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory(6)->create([
            'category_id' => $category->id,
        ]);

        Product::factory(10)->create([
            'category_id' => $category2->id,
        ]);

        $response = $this->getJson('/api/products?categoryId='.$category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 6],
            ]);
    }
}
