<?php

namespace Domain\User\States;

class Activated extends UserState
{
    public function color(): string
    {
        return 'green';
    }

    public function __toString(): string
    {
        return 'activated';
    }
}
