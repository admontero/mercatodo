<?php

namespace Database\Seeders;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
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
