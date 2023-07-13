<?php

namespace Database\Seeders;

use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'email' => 'admin@test.com',
                'password' => bcrypt('12345678'),
            ]);

        User::factory(800)
            ->customer()
            ->withCustomerProfile()
            ->create()
            ->each(function ($user) {
                $country = Country::inRandomOrder()->first();
                $state = State::where('country_id', $country->id)->inRandomOrder()->first();
                $city = City::where('state_id', $state->id)->inRandomOrder()->first();

                $user->profileable()->update([
                    'country_id' => $country->id,
                    'state_id' => $state->id,
                    'city_id' => $city->id,
                ]);
            });

        User::factory()
            ->customer()
            ->unverified()
            ->withCustomerProfile([
                'first_name' => 'Customer',
                'last_name' => 'User',
            ])
            ->create([
                'email' => 'customer@test.com',
                'password' => bcrypt('12345678'),
            ]);
    }
}
