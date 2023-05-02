<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
            ->count(800)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['category_id' => Category::inRandomOrder()->first()],
            ))
            ->create();
    }
}
