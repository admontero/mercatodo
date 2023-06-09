<?php

namespace Domain\Product\States;

class Activated extends ProductState
{
    public function __toString(): string
    {
        return 'activated';
    }
}
