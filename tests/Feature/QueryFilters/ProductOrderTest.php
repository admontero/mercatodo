<?php

namespace Tests\Feature\QueryFilters;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductOrderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Product::factory()->create([
            'price' => 80000,
            'created_at' => now()->subDays(4),
        ]);

        Product::factory()->create([
            'price' => 120000,
            'created_at' => now()->subDays(3),
        ]);

        Product::factory()->create([
            'price' => 750000,
            'created_at' => now()->subDays(2),
        ]);

        Product::factory()->create([
            'price' => 52000,
            'created_at' => now()->subDays(1),
        ]);

        Product::factory()->create([
            'price' => 350000,
        ]);
    }

    /**
     * @test
     */
    public function it_is_ordering_products_by_oldest(): void
    {
        $response = $this->getJson('/api/products?order=orderByOldest');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);

        $this->assertEquals($response['data'][0]['price'], 80000);
    }

    /**
     * @test
     */
    public function it_is_ordering_products_by_latest(): void
    {
        $response = $this->getJson('/api/products');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);

        $this->assertEquals($response['data'][0]['price'], 350000);
    }

    /**
     * @test
     */
    public function it_is_ordering_products_by_greater_price(): void
    {
        $response = $this->getJson('/api/products?order=orderByPriceDESC');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);

        $this->assertEquals($response['data'][0]['price'], 750000);
    }

    /**
     * @test
     */
    public function it_is_ordering_products_by_lowest_price(): void
    {
        $response = $this->getJson('/api/products?order=orderByPriceASC');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);

        $this->assertEquals($response['data'][0]['price'], 52000);
    }

    /**
     * @test
     */
    public function it_is_ordering_products_by_default_if_order_query_string_is_wrong(): void
    {
        $response = $this->getJson('/api/products?order=orderByNothing');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 5],
            ]);

        $this->assertEquals($response['data'][0]['price'], 350000);
    }
}
