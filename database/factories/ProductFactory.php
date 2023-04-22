<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
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
            'name' => fake()->name(),
            'code' => fake()->unique()->ean8(),
            'description' => fake()->realText(),
            'price' => fake()->randomNumber(6, false),
            'image' => fake()->imageUrl(640, 480, 'cats'),
        ];
    }
}
