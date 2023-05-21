<?php

namespace Domain\User\States;

use Domain\Shared\Contracts\StateInterface;
use Domain\User\Models\User;

class ActiveStatus implements StateInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $this->inactive();
    }

    public function inactive(): void
    {
        $this->nextStatus(InactiveStatus::class)->save();
    }

    private function nextStatus(string $status): User
    {
        return tap($this->user, function ($user) use ($status) {
            $user->status = $status;
        });
    }

    public function __toString(): string
    {
        return 'activated';
    }
}