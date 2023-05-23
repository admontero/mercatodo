<?php

namespace Domain\Order\States;

class Paid extends OrderState
{
    public function color(): string
    {
        return 'green';
    }
}
