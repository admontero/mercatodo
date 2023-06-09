<?php

namespace Domain\Order\States;

class Canceled extends OrderState
{
    public function __toString(): string
    {
        return 'canceled';
    }
}
