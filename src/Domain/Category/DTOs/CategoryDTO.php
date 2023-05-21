<?php

namespace Domain\Category\DTOs;

use App\ApiAdmin\Category\Requests\StoreCategoryRequest;
use App\ApiAdmin\Category\Requests\UpdateCategoryRequest;

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
