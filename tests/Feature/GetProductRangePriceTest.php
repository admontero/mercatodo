<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetProductRangePriceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_get_the_product_range_price(): void
    {
        Product::factory()->create([
            'price' => 158500
        ]);

        Product::factory()->create([
            'price' => 52000
        ]);

        Product::factory()->create([
            'price' => 389000
        ]);

        Product::factory()->create([
            'price' => 256200
        ]);

        $response = $this->getJson(route('api.products.range-price'));

        $response
            ->assertSuccessful()
            ->assertJsonStructure([
                'min_price', 'max_price'
            ])
            ->assertJsonFragment(
                [
                    'min_price' => 52000,
                    'max_price' => 389000,
                ]
            );
    }
}
