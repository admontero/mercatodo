<?php

namespace App\Models;

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
     * @return MorphOne<User>
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profileable');
    }
}
