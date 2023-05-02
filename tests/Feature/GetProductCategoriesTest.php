<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetProductCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_get_all_categories_name(): void
    {
        $categories = Category::factory(20)->create();

        $response = $this->getJson(route('api.products.categories'));

        $response
            ->assertOk()
            ->assertJsonCount(20)
            ->assertJsonFragment([
                'id' => $categories->first()->id,
                'name' => $categories->first()->name,
            ]);
    }
}
