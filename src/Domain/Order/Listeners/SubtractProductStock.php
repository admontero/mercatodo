<?php

namespace Domain\Order\Listeners;

use Domain\Order\Events\OrderCreated;
use Domain\Product\Services\ProductService;
use Illuminate\Support\Facades\Log;

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
        Log::channel('placetopay')->info("[PAY]: Restamos el stock a los productos de la orden #{$event->order->code}");
        foreach ($event->order->products as $product) {
            $product->stock -= $product->getRelationValue('pivot')->quantity;
            $product->save();
        }
    }
}
