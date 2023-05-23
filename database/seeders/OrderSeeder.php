<?php

namespace Database\Seeders;

use Domain\Order\Models\Order;
use Domain\OrderProduct\Models\OrderProduct;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()
            ->count(200)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => User::customer()->inRandomOrder()->first()],
            ))
            ->create()
            ->each(function ($order) {
                OrderProduct::factory()
                    ->count(random_int(1, 5))
                    ->state(new Sequence(function (Sequence $sequence) use ($order) {
                        $product = Product::inRandomOrder()->first();
                        return [
                            'unit_price' => $product->price,
                            'quantity' => random_int(1, 3),
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                        ];
                    }))
                    ->create();

                $order->update([
                    'total_price' => $order->products->map(function ($p) {
                        return $p->pivot->unit_price * $p->pivot->quantity;
                    })->sum(),
                ]);
            });
    }
}
