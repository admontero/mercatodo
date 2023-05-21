<?php

namespace Database\Seeders;

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
            ->create();

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
