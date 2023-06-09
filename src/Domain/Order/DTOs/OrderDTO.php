<?php

namespace Domain\Order\DTOs;

use App\ApiCustomer\Order\Requests\StoreOrderRequest;

class OrderDTO
{
    public function __construct(
        public readonly string $total,
        public readonly string $provider,
        public readonly string $products,
    ) {
    }

    public static function fromStoreRequest(StoreOrderRequest $request): OrderDTO
    {
        return new self(
            total: $request->validated('total'),
            provider: $request->validated('provider'),
            products: $request->validated('products'),
        );
    }
}
