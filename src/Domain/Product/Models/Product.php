<?php

namespace Domain\Product\Models;

use Database\Factories\ProductFactory;
use Domain\Category\Models\Category;
use Domain\Order\Models\Order;
use Domain\Product\States\Activated;
use Domain\Product\States\ProductState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\ModelStates\HasStates;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasStates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
    ];

    protected $casts = [
        'state' => ProductState::class,
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\ProductFactory
     */
    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Category,Product>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return BelongsToMany<Order>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('unit_price', 'quantity')
            ->withTimestamps();
    }

    /**
     * @param Builder<\Domain\User\Models\User> $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('state', Activated::class);
    }
}
