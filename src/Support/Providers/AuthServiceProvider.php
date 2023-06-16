<?php

namespace Support\Providers;

// use Illuminate\Support\Facades\Gate;

use Domain\Role\Enums\RoleEnum;
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
        Gate::define('access-admin-dashboard', function (User $user) {
            if ($user->hasRole(RoleEnum::ADMIN->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-customer-list', function (User $user) {
            if ($user->hasRole(RoleEnum::ADMIN->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-customer-edit', function (User $user, User $model) {
            if ($user->hasRole(RoleEnum::ADMIN->value) and $model->hasRole(RoleEnum::CUSTOMER->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-category-views', function (User $user) {
            if ($user->hasRole(RoleEnum::ADMIN->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-product-views', function (User $user) {
            if ($user->hasRole(RoleEnum::ADMIN->value)) {
                return true;
            }

            return false;
        });

        /** Customer Gates */
        Gate::define('access-profile-edit', function (User $user) {
            if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-checkout-index', function (User $user) {
            if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-payment-return', function (User $user) {
            if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
                return true;
            }

            return false;
        });

        Gate::define('access-order-list', function (User $user) {
            if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
                return true;
            }

            return false;
        });
    }
}
