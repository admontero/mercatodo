<?php

namespace Database\Seeders;

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
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory()->customer()->unverified()->create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'email' => 'customer@test.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
