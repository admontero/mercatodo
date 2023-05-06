<?php

namespace App\DataTransferObjects;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryDTO
{
    public function __construct(
        public readonly string $name,
    ) {
    }

    public static function fromStoreRequest(StoreCategoryRequest $request): CategoryDTO
    {
        return new self(
            name: $request->validated('name'),
        );
    }

    public static function fromUpdateRequest(UpdateCategoryRequest $request): CategoryDTO
    {
        return new self(
            name: $request->validated('name'),
        );
    }
}
