<?php

namespace App\Models\UserStatuses;

use App\Contracts\StateInterface;
use App\Models\User;

class InactiveStatus implements StateInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $this->active();
    }

    public function active(): void
    {
        $this->nextStatus(ActiveStatus::class)->save();
    }

    private function nextStatus($status): User
    {
        return tap($this->user, function ($user) use ($status) {
            $user->status = $status;
        });
    }

    public function __toString(): string
    {
        return 'inactivated';
    }
}
