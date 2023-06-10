<?php

namespace Domain\Order\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Completed::class)
            ->allowTransition(Pending::class, Canceled::class)
        ;
    }
}
