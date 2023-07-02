<?php

namespace Tests\Unit\Services;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Assert;
use Mockery;
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

        (new ProductService())->checkStockAvailable($product, 7);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_get_product_by_id_and_it_not_exists(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El producto a comprar no existe.');

        Product::factory()->create(['id' => 1, 'stock' => 5]);

        (new ProductService())->getProductById(2);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_the_creation_file_fail(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al abrir el archivo.');

        $mock = Mockery::mock('Domain\Product\Services\ProductService')->makePartial();

        $mock->shouldReceive('openFile')
            ->once()
            ->with('exports/products.csv', 'w')
            ->andReturn(false);

        $mock->createExcelFile();
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_the_read_excel_file_fail(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al abrir el archivo.');

        $mock = Mockery::mock('Domain\Product\Services\ProductService')->makePartial();

        $mock->shouldReceive('openFile')
            ->once()
            ->with('imports/products.csv', 'r')
            ->andReturn(false);

        $mock->readExcelFile('imports/products.csv');
    }

    /**
     * @test
     */
    public function it_create_the_export_excel_file(): void
    {
        $products = Product::factory(3)->create();

        $path = (new ProductService)->createExcelFile();

        $fileContent = "name,code,description,price,stock,category\n";

        foreach ($products as $product) {
            $name = str_replace('"', str_repeat('"', 2), $product->name);
            $description = str_replace('"', str_repeat('"', 2), $product->description);
            $category = str_replace('"', str_repeat('"', 2), $product->category->name);

            $fileContent .= "\"{$name}\",{$product->code},\"{$description}\",{$product->price},{$product->stock},\"{$category}\"\n";
        }

        $this->assertStringMatchesFormatFile($path, $fileContent);
    }
}
