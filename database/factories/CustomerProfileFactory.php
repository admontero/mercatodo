<?php

namespace Database\Factories;

use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\State\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerProfile::class;

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
        ];
    }

    /**
     * Add a country to the profile
     */
    public function withCountry(): static
    {
        return $this->state(fn (array $attributes) => [
            'country_id' => Country::factory(),
        ]);
    }

    /**
     * Add a state to the profile
     */
    public function withState(): static
    {
        return $this->state(fn (array $attributes) => [
            'state_id' => State::factory(),
        ]);
    }

    /**
     * Add a city to the profile
     */
    public function withCity(): static
    {
        return $this->state(fn (array $attributes) => [
            'city_id' => City::factory(),
        ]);
    }
}
