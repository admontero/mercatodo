<?php

namespace Domain\Shared\Contracts;

interface StateInterface
{
    public function handle(): void;
}
