<?php

namespace Domain\CustomerProfile\Models;

use Database\Factories\CustomerProfileFactory;
use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'document_type',
        'document',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'phone',
        'cell_phone',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'country:id,name',
        'state:id,name',
        'city:id,name',
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\CustomerProfileFactory
     */
    protected static function newFactory(): Factory
    {
        return CustomerProfileFactory::new();
    }

    /**
     * @return MorphOne<User>
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profileable');
    }

    /**
     * @return BelongsTo<Country,CustomerProfile>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo<State,CustomerProfile>
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * @return BelongsTo<City,CustomerProfile>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
