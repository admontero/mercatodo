<?php

namespace Domain\Shared\Contracts;

use Services\Payments\PaymentBase;

interface PaymentFactoryInterface
{
    public function initializePayment(string $type): PaymentBase;
}
