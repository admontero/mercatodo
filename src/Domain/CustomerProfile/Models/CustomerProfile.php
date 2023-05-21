<?php

namespace Domain\CustomerProfile\Models;

use Database\Factories\CustomerProfileFactory;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'document_type',
        'document',
        'address',
        'phone',
        'cell_phone',
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
}
