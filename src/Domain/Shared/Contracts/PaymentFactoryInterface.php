<?php

namespace Domain\Shared\Contracts;

use Services\PaymentBase;

interface PaymentFactoryInterface
{
    public function initializePayment(string $type): PaymentBase;
}
