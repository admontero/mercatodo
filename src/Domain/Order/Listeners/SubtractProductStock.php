<?php

namespace Domain\Order\Listeners;

use Domain\Order\Events\OrderCreated;
use Domain\Product\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubtractProductStock
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        foreach ($event->order->products as $p) {
            $product = (new ProductService())->getProductById($p->id);
            $product->stock -= $p->getRelationValue('pivot')->quantity;
            $product->save();
        }
    }
}
