<?php

namespace Domain\User\States;

class Inactivated extends UserState
{
    public function __toString(): string
    {
        return 'inactivated';
    }
}
