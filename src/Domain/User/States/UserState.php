<?php

namespace Domain\User\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserState extends State
{
    abstract public function color(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Activated::class)
            ->allowTransition(Activated::class, Inactivated::class)
            ->allowTransition(Inactivated::class, Activated::class)
        ;
    }
}
