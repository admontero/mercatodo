<?php

namespace Database\Seeders;

use Domain\Country\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colombia = Country::where('name', 'Colombia')->first();

        $states = [
            ['name' => 'Amazonas'],
            ['name' => 'Antioquia'],
            ['name' => 'Arauca'],
            ['name' => 'Atlántico'],
            ['name' => 'Bogotá'],
            ['name' => 'Bolívar'],
            ['name' => 'Boyacá'],
            ['name' => 'Caldas'],
            ['name' => 'Caquetá'],
            ['name' => 'Casanare'],
            ['name' => 'Cauca'],
            ['name' => 'Cesar'],
            ['name' => 'Chocó'],
            ['name' => 'Córdoba'],
            ['name' => 'Cundinamarca'],
            ['name' => 'Guainía'],
            ['name' => 'Guaviare'],
            ['name' => 'Huila'],
            ['name' => 'La Guajira'],
            ['name' => 'Magdalena'],
            ['name' => 'Meta'],
            ['name' => 'Nariño'],
            ['name' => 'Norte de Santander'],
            ['name' => 'Putumayo'],
            ['name' => 'Quindío'],
            ['name' => 'Risaralda'],
            ['name' => 'San Andrés y Provicencia'],
            ['name' => 'Santander'],
            ['name' => 'Sucre'],
            ['name' => 'Tolima'],
            ['name' => 'Valle del Cauca'],
            ['name' => 'Vaupés'],
            ['name' => 'Vichada'],
        ];

        $states = array_map(function ($state) use ($colombia) {
            return [...$state, 'country_id' => $colombia->id, 'created_at' => now(), 'updated_at' => now()];
        }, $states);

        DB::table('states')->insert($states);
    }
}
