<?php

namespace Services;

use Domain\Shared\Contracts\PaymentFactoryInterface;
use Exception;

class PaymentFactory implements PaymentFactoryInterface
{
    public function initializePayment(string $type): PaymentBase
    {
        return match($type) {
            'PlaceToPay' => new PlaceToPayPayment(),
            default => throw new Exception('Medio de pago no soportado'),
        };
    }
}
