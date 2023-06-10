<?php

namespace Domain\User\Policies;

use Domain\Role\Enums\RoleEnum;
use Domain\User\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyCustomer(User $user): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewCustomer(User $user, User $model): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value) && $model->hasRole(RoleEnum::CUSTOMER->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::CUSTOMER->value) && $model->hasRole(RoleEnum::CUSTOMER->value) && $user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCustomer(User $user, User $model): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value) and $model->hasRole(RoleEnum::CUSTOMER->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCustomerProfile(User $user, User $model): bool
    {
        if ($user->hasRole(RoleEnum::CUSTOMER->value) and $model->hasRole(RoleEnum::CUSTOMER->value) and $user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model status.
     */
    public function updateStatus(User $user, User $model): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value) and $model->hasRole(RoleEnum::CUSTOMER->value)) {
            return true;
        }

        return false;
    }
}
