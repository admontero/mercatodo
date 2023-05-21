<?php

namespace Domain\Product\DTOs;

use App\ApiAdmin\Product\Requests\StoreProductRequest;
use App\ApiAdmin\Product\Requests\UpdateProductRequest;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $category_id,
        public readonly ?string $image,
    ) {
    }

    public static function fromStoreRequest(StoreProductRequest $request, ?string $image): ProductDTO
    {
        return new self(
            name: $request->validated('name'),
            code: $request->validated('code'),
            description: $request->validated('description'),
            price: $request->validated('price'),
            stock: $request->validated('stock', 0),
            category_id: $request->validated('category_id'),
            image: $image,
        );
    }

    public static function fromUpdateRequest(UpdateProductRequest $request, ?string $image): ProductDTO
    {
        return new self(
            name: $request->validated('name'),
            code: $request->validated('code'),
            description: $request->validated('description'),
            price: $request->validated('price'),
            stock: $request->validated('stock', 0),
            category_id: $request->validated('category_id'),
            image: $image,
        );
    }
}
