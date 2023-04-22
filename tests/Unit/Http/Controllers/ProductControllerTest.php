<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_method_must_returns_the_product_index_view(): void
    {
        $this->withoutVite();

        $response = $this->get(route('products.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('products.index');
    }
}
