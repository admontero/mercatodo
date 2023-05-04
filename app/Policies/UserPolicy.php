<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

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
    public function view(User $user, User $model): bool
    {
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
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
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
