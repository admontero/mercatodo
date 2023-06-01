<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['code' => 'CO', 'name' => 'Colombia', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('countries')->insertOrIgnore($countries);
    }
}
