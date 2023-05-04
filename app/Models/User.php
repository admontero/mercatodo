<?php

namespace App\Models;

use App\Models\UserStatuses\ActiveStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

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
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ActiveStatus::class,
    ];

    /**
     * @var array<int, string>
     */
    protected $with = ['profileable'];

    /**
     * @var string
     */
    protected $guard_name = 'api';

    protected static function booted(): void
    {
        static::created(function ($user) {
            $user->update(['status' => ActiveStatus::class]);
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

    /**
     * @return MorphTo<\Illuminate\Database\Eloquent\Model,User>
     */
    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeCustomer(Builder $query): void
    {
        $query->whereRelation('roles', 'name', 'customer');
    }
}
