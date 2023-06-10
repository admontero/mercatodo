<?php

namespace Domain\Order\DTOs;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;

class OrderDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly array $products,
    ) {
    }

    public static function fromStoreRequest(StoreOrderRequest $request): OrderDTO
    {
        return new self(
            provider: $request->validated('provider'),
            products: $request->validated('products'),
        );
    }
}
