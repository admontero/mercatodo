<?php

namespace Tests\Unit\Http\Resources;

use App\ApiAdmin\Product\Resources\ProductResource;
use Domain\Order\Models\Order;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
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

    /**
     * @test
     */
    public function a_product_resource_must_have_the_required_fields_when_pivot_is_loaded(): void
    {
        $order = Order::factory()->create();

        OrderProduct::factory()
            ->count(random_int(1, 5))
            ->state(new Sequence(function (Sequence $sequence) use ($order) {
                $product = Product::factory()->create();

                return [
                    'price' => $product->price,
                    'quantity' => random_int(1, 3),
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ];
            }))
            ->create();

        $product = $order->products->first();

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
        $this->assertEquals($product->pivot->price, $productResource['pivot']['price']);
        $this->assertEquals($product->pivot->quantity, $productResource['pivot']['quantity']);
        $this->assertEquals($product->created_at->diffForHumans(), $productResource['ago']);
    }
}
