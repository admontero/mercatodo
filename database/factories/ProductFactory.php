<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStatuses\ActiveStatus;
use App\Models\ProductStatuses\InactiveStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Product $product) {
            Category::count()
                ? $product->category_id = Category::inRandomOrder()->first()->id
                : $product->category_id = Category::factory()->create()->id;

            return $product;
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(2),
            'code' => fake()->unique()->ean8(),
            'description' => fake()->realText(),
            'price' => fake()->randomNumber(6, false),
            'image' => 'https://picsum.photos/640/480',
        ];
    }

    /**
     * Indicate that the user is a activated.
     */
    public function activated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ActiveStatus::class,
            ];
        });
    }

    /**
     * Indicate that the user is a inactivated.
     */
    public function inactivated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => InactiveStatus::class,
            ];
        });
    }
}
