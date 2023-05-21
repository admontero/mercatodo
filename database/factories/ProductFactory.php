<?php

namespace Database\Factories;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\States\ActiveStatus;
use Domain\Product\States\InactiveStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

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
            'stock' => fake()->randomNumber(2),
            'category_id' => Category::factory(),
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
