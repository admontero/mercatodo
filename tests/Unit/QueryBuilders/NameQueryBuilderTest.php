<?php

namespace Tests\Unit\QueryBuilders;

use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NameQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_is_filtering_by_name(): void
    {
        Product::factory()->create([
            'name' => 'balon golty',
        ]);

        Product::factory()->create([
            'name' => 'balon mikasa',
        ]);

        Product::factory()->create([
            'name' => 'celular iphone',
        ]);

        Product::factory()->create([
            'name' => 'televisor sony',
        ]);

        $response = $this->getJson('/api/products?name=balon');

        $response
            ->assertStatus(200)
            ->assertJson([
                'meta' => ['total' => 2],
            ]);
    }
}
