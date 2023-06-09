<?php

namespace Tests\Unit\Services;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function upload_image_can_return_null_if_request_not_has_a_file(): void
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Pista Hot Wheels',
            'code' => 'PHW-23423',
            'description' => '',
            'price' => 159999,
            'stock' => 6,
            'category_id' => Category::factory()->create(),
        ];

        $request = Request::create(route('api.admin.products.update', $product), 'PUT', $data);

        $this->assertNull((new ProductService)->uploadImage($request, $product));
    }
}
