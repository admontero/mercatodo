<?php

namespace Tests\Unit\Http\Resources;

use App\ApiAdmin\Product\Resources\ProductResource;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_product_resource_must_have_the_required_fields(): void
    {
        $product = Product::factory()->create();

        $productResource = ProductResource::make($product)->resolve();

        $this->assertEquals($product->id, $productResource['id']);
        $this->assertEquals($product->name, $productResource['name']);
        $this->assertEquals($product->slug, $productResource['slug']);
        $this->assertEquals($product->code, $productResource['code']);
        $this->assertEquals($product->description, $productResource['description']);
        $this->assertEquals($product->price, $productResource['price']);
        $this->assertEquals($product->stock, $productResource['stock']);
        $this->assertEquals($product->image, $productResource['image']);
        $this->assertEquals($product->state, $productResource['state']);
        $this->assertEquals($product->created_at->diffForHumans(), $productResource['ago']);
    }
}
