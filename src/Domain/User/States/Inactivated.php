<?php

namespace Domain\User\States;

class Inactivated extends UserState
{
    public function color(): string
    {
        return 'yellow';
    }

    public function __toString(): string
    {
        return 'inactivated';
    }
}
