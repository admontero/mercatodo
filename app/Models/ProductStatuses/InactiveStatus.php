<?php

namespace App\Models\ProductStatuses;

use App\Contracts\StateInterface;
use App\Models\Product;

class InactiveStatus implements StateInterface
{
    private $product;

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

    private function nextStatus($status): Product
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
