<?php

namespace Tests\Feature\QueryFilters;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NameFilterTest extends TestCase
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
