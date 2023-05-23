<?php

namespace Domain\OrderProduct\Models;

use Database\Factories\OrderProductFactory;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    use HasFactory;

    protected $table = 'order_product';

    protected $fillable = [
        'unit_price',
        'quantity',
        'order_id',
        'product_id',
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\OrderProductFactory
     */
    protected static function newFactory(): Factory
    {
        return OrderProductFactory::new();
    }

    /**
     * @return BelongsTo<Order,OrderProduct>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo<Product,OrderProduct>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
