<?php

namespace Domain\City\Models;

use Database\Factories\CityFactory;
use Domain\State\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_id',
    ];

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\CityFactory
     */
    protected static function newFactory(): Factory
    {
        return CityFactory::new();
    }

    /**
     * @return BelongsTo<State,City>
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
