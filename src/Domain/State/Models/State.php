<?php

namespace Domain\State\Models;

use Database\Factories\StateFactory;
use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\StateFactory
     */
    protected static function newFactory(): Factory
    {
        return StateFactory::new();
    }

    /**
     * @return BelongsTo<Country,State>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return HasMany<City>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
