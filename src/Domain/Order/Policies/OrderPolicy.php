<?php

namespace Domain\Order\Policies;

use Domain\Order\Models\Order;
use Domain\User\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('customer')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function createOrder(User $user): bool
    {
        if ($user->hasRole('customer')) {
            return true;
        }

        return false;
    }
}
