<?php

namespace Domain\Order\States;

class Canceled extends OrderState
{
    public function color(): string
    {
        return 'red';
    }
}
