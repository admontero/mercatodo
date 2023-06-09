<?php

namespace Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_returns_the_product_index_view(): void
    {
        $this->withoutVite();

        $response = $this->get(route('products.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('products.index');
    }
}
