<?php

namespace Domain\Order\States;

class Incompleted extends OrderState
{
    public function __toString(): string
    {
        return 'incompleted';
    }
}
