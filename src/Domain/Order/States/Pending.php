<?php

namespace Domain\Order\States;

class Pending extends OrderState
{
    public function color(): string
    {
        return 'yellow';
    }
}
