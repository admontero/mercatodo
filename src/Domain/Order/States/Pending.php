<?php

namespace Domain\Order\States;

class Pending extends OrderState
{
    public function __toString(): string
    {
        return 'pending';
    }
}
