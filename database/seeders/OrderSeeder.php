<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Domain\Order\Models\Order;
use Domain\Order\States\Canceled;
use Domain\Order\States\Completed;
use Domain\Order\States\Pending;
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
            ->count(400)
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
                            'price' => $product->price,
                            'quantity' => random_int(1, 3),
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                        ];
                    }))
                    ->create();

                $states = [Pending::class, Canceled::class, Completed::class];

                $date = Carbon::createFromTimestamp(rand(now()->subYears(2)->timestamp, now()->timestamp));

                $order->update([
                    'total' => $order->products->map(function ($p) {
                        return $p->pivot->price * $p->pivot->quantity;
                    })->sum(),
                    'state' => $states[array_rand($states)],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            });
    }
}
