<?php

namespace Domain\Product\States;

class Activated extends ProductState
{
    public function color(): string
    {
        return 'green';
    }

    public function __toString(): string
    {
        return 'activated';
    }
}
