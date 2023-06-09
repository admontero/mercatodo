<?php

namespace Domain\User\Policies;

use Domain\User\Models\User;

class UserPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyCustomer(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewCustomer(User $user, User $model): bool
    {
        if ($user->hasRole('admin') && $model->hasRole('customer')) {
            return true;
        }

        if ($user->hasRole('customer') && $model->hasRole('customer') && $user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCustomer(User $user, User $model): bool
    {
        if ($user->hasRole('admin') and $model->hasRole('customer')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCustomerProfile(User $user, User $model): bool
    {
        if ($user->hasRole('customer') and $model->hasRole('customer') and $user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model status.
     */
    public function updateStatus(User $user, User $model): bool
    {
        if ($user->hasRole('admin') and $model->hasRole('customer')) {
            return true;
        }

        return false;
    }
}
