<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(800)->create();

        User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
        ]);

        $customer = User::factory()->customer()->unverified()->create([
            'email' => 'customer@test.com',
            'password' => bcrypt('12345678'),
        ]);

        Customer::create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'user_id' => $customer->id
        ]);
    }
}
