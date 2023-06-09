<?php

namespace Domain\User\States;

class Activated extends UserState
{
    public function __toString(): string
    {
        return 'activated';
    }
}
