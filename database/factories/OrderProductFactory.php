<?php

namespace Database\Factories;

use Domain\Order\Models\Order;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => fake()->randomNumber(6, false),
            'quantity' => fake()->randomNumber(1),
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
