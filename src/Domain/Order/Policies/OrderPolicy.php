<?php

namespace Domain\Order\Policies;

use Domain\Role\Enums\RoleEnum;
use Domain\User\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function createOrder(User $user): bool
    {
        if ($user->hasRole(RoleEnum::CUSTOMER->value)) {
            return true;
        }

        return false;
    }
}
