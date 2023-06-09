<?php

namespace Domain\Product\States;

class Inactivated extends ProductState
{
    public function __toString(): string
    {
        return 'inactivated';
    }
}
