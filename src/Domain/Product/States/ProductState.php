<?php

namespace Domain\Product\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProductState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Activated::class)
            ->allowTransition(Activated::class, Inactivated::class)
            ->allowTransition(Inactivated::class, Activated::class);
    }
}
