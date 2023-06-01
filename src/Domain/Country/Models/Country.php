<?php

namespace Domain\Country\Models;

use Database\Factories\CountryFactory;
use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\State\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\CountryFactory
     */
    protected static function newFactory(): Factory
    {
        return CountryFactory::new();
    }

    /**
     * @return HasMany<CustomerProfile>
     */
    public function customer_profiles(): HasMany
    {
        return $this->hasMany(CustomerProfile::class);
    }

    /**
     * @return HasMany<State>
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
