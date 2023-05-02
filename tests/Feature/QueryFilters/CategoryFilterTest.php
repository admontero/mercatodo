<?php

namespace Tests\Feature\QueryFilters;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryFilterTest extends TestCase
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
            'category_id' => $category2->id
        ]);

        $response = $this->getJson('/api/products?categoryId=' . $category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 6],
            ]);
    }
}
