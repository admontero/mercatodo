<?php

namespace Domain\Product\Models;

use Database\Factories\ProductFactory;
use Domain\Category\Models\Category;
use Domain\Product\States\ActiveStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

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

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ActiveStatus::class,
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

    protected static function booted(): void
    {
        static::created(function ($product) {
            $product->update(['status' => ActiveStatus::class]);
        });
    }

    public function changeStatus(): void
    {
        $this->status->handle();
    }

    public function getStatusAttribute(string $status): mixed
    {
        return new $status($this);
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
     * @param Builder<\Domain\User\Models\User> $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', ActiveStatus::class);
    }
}
