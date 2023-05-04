<?php

namespace App\Models\ProductStatuses;

use App\Contracts\StateInterface;
use App\Models\Product;

class ActiveStatus implements StateInterface
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
    {
        $this->inactive();
    }

    public function inactive(): void
    {
        $this->nextStatus(InactiveStatus::class)->save();
    }

    private function nextStatus(string $status): Product
    {
        return tap($this->product, function ($product) use ($status) {
            $product->status = $status;
        });
    }

    public function __toString(): string
    {
        return 'activated';
    }
}
