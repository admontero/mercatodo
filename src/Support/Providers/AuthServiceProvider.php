<?php

namespace Support\Providers;

// use Illuminate\Support\Facades\Gate;

use Domain\User\Models\User;
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
        //'App\Models\Model' => 'App\Policies\ModelPolicy',
        'Domain\User\Models\User' => 'Domain\User\Policies\UserPolicy',
        'Domain\Category\Models\Category' => 'Domain\Category\Policies\CategoryPolicy',
        'Domain\Product\Models\Product' => 'Domain\Product\Policies\ProductPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /** Admin Gates */
        Gate::define('view-customer', function (User $user) {
            if ($user->hasRole('admin')) {
                return true;
            }

            return false;
        });

        Gate::define('update-customer', function (User $user, User $model) {
            if ($user->hasRole('admin') and $model->hasRole('customer')) {
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
        /** */

        /** Customer Gates */
        Gate::define('update-profile', function (User $user) {
            if ($user->hasRole('customer')) {
                return true;
            }

            return false;
        });
        /** */
    }
}
