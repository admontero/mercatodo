<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Start Admin Gates #

        Gate::define('view-customer', function (User $user) {
            if ($user->hasRole('admin')) {
                return true;
            }

            return false;
        });

        Gate::define('update-customer', function (User $user, Customer $customer) {
            if ($user->hasRole('admin') and $customer->user->hasRole('customer')) {
                return true;
            }

            return false;
        });

        Gate::define('manage-category', function (User $user) {
            if ($user->hasRole('admin')) {
                return true;
            }

            return false;
        });

        Gate::define('update-profile', function (User $user) {
            if ($user->hasRole('customer')) {
                return true;
            }

            return false;
        });

        // End Admin Gates #
    }
}
