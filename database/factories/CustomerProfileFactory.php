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
            'country_id' => Country::factory(),
            'state_id' => State::factory(),
            'city_id' => City::factory(),
        ];
    }
}
