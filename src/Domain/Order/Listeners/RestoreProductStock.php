<?php

namespace Domain\Order\Listeners;

use Domain\Order\Events\OrderCanceled;
use Domain\Product\Services\ProductService;
use Illuminate\Support\Facades\Log;

class RestoreProductStock
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
    public function handle(OrderCanceled $event): void
    {
        foreach ($event->order->products as $p) {
            Log::channel('placetopay')->info('[PAY]: Restauramos el stock de los productos');
            $product = (new ProductService())->getProductById($p->id);
            $product->stock += $p->getRelationValue('pivot')->quantity;
            $product->save();
        }
    }
}
