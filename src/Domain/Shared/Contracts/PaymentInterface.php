<?php

namespace Domain\Shared\Contracts;

interface PaymentInterface
{
    public function pay(): void;
}
