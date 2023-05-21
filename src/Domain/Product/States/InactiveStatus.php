<?php

namespace Domain\Product\States;

use Domain\Product\Models\Product;
use Domain\Shared\Contracts\StateInterface;

class InactiveStatus implements StateInterface
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
    {
        $this->active();
    }

    public function active(): void
    {
        $this->nextStatus(ActiveStatus::class)->save();
    }

    private function nextStatus(string $status): Product
    {
        return tap($this->product, function ($product) use ($status) {
            $product->status = $status;
        });
    }

    public function __toString(): string
    {
        return 'inactivated';
    }
}
