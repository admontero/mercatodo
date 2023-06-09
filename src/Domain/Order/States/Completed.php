<?php

namespace Domain\Order\States;

class Completed extends OrderState
{
    public function __toString(): string
    {
        return 'completed';
    }
}
