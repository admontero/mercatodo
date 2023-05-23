<?php

namespace Domain\User\Models;

use Database\Factories\UserFactory;
use Domain\Order\Models\Order;
use Domain\User\States\UserState;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\ModelStates\HasStates;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasStates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'state' => UserState::class,
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'profileable'
    ];

    /**
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * Create a new factory instance for the model.
     * @return \Database\Factories\UserFactory
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    /**
     * @return MorphTo<\Illuminate\Database\Eloquent\Model,User>
     */
    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeCustomer(Builder $query): void
    {
        $query->whereRelation('roles', 'name', 'customer');
    }
}
