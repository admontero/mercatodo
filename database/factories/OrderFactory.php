<?php

namespace Database\Factories;

use Domain\Order\Models\Order;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_price' => fake()->randomNumber(7, false),
            'user_id' => User::factory()->customer(),
        ];
    }
}
