<?php

namespace App\Models;

use App\Models\UserStatuses\ActiveStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'document_type',
        'document',
        'address',
        'phone',
        'cell_phone',
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

    protected $attributes = [
        'status' => ActiveStatus::class,
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->update(['status' => \App\Models\UserStatuses\ActiveStatus::class]);
        });
    }

    public function changeStatus() : void
    {
        $this->status->handle();
    }

    public function getStatusAttribute($status)
    {
        return new $status($this);
    }

    public function scopeCustomer(Builder $query): void
    {
        $query->whereRelation('roles', 'name', 'customer');
    }
}
