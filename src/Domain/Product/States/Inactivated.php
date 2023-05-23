<?php

namespace Domain\Product\States;

class Inactivated extends ProductState
{
    public function color(): string
    {
        return 'green';
    }

    public function __toString(): string
    {
        return 'inactivated';
    }
}
