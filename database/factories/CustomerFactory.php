<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'user_id' => User::factory()->activated(),
        ];
    }

    /**
     * Indicate that the user is a inactivated.
     */
    public function inactivated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory()->inactivated(),
            ];
        });
    }
}
