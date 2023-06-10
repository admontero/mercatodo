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

        $this->assertNull((new ProductService())->uploadImage($request, $product));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_qty_is_greater_that_stock(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No hay existencias suficientes para crear la orden.');

        $product = Product::factory()->create(['stock' => 5]);

        (new ProductService())->checkStockAvailable($product->id, 7);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_check_the_stock_and_it_not_exists(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El producto a comprar no existe.');

        Product::factory()->create(['id' => 1, 'stock' => 5]);

        (new ProductService())->checkStockAvailable(2, 7);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_get__product_by_id_and_it_not_exists(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El producto a comprar no existe.');

        Product::factory()->create(['id' => 1, 'stock' => 5]);

        (new ProductService())->getProductById(2);
    }


}
