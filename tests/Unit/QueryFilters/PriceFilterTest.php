<?php

namespace Tests\Unit\QueryFilters;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Product::factory()->create([
            'price' => 80000,
        ]);

        Product::factory()->create([
            'price' => 120000,
        ]);

        Product::factory()->create([
            'price' => 750000,
        ]);

        Product::factory()->create([
            'price' => 52000,
        ]);

        Product::factory()->create([
            'price' => 350000,
        ]);
    }

    /**
     * @test
     */
    public function it_is_filtering_by_price(): void
    {
        $response = $this->getJson('/api/products?minPrice=150000&maxPrice=1000000');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 2],
            ]);
    }

    /**
     * @test
     */
    public function it_is_not_filtering_by_price_if_min_price_is_greater_than_max_price(): void
    {
        $response = $this->getJson('/api/products?minPrice=850000&maxPrice=800000');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);
    }
}
