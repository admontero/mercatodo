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
        Log::channel('placetopay')->info('[PAY]: Restamos el stock a los productos de la orden');
        foreach ($event->order->products as $p) {
            $product = (new ProductService())->getProductById($p->id);
            $product->stock -= $p->getRelationValue('pivot')->quantity;
            $product->save();
        }
    }
}
