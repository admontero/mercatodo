<?php

namespace Database\Factories;

use Domain\Order\Models\Order;
use Domain\Order\States\Incompleted;
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
            'code' => time() . random_int(1000, 9999),
            'currency' => 'COP',
            'provider' => 'PlaceToPay',
            'total' => fake()->randomNumber(7, false),
            'user_id' => User::factory()->customer(),
            'state' => Incompleted::class,
        ];
    }
}
