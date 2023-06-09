<?php

namespace Domain\Order\Models;

use Database\Factories\OrderFactory;
use Domain\Order\States\OrderState;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\ModelStates\HasStates;

/**
 * @property \Domain\Order\States\OrderState $state
 */
class Order extends Model
{
    use HasFactory;
    use HasStates;

    protected $fillable = [
        'code',
        'total',
        'currency',
        'provider',
        'request_id',
        'url',
        'user_id',
    ];

    protected $casts = [
        'state' => OrderState::class,
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\OrderFactory
     */
    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('price', 'quantity')
            ->withTimestamps();
    }

    /**
     * @return BelongsTo<User,Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
