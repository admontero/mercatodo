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

    public static function fromStoreRequest(StoreCategoryRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
        );
    }

    public static function fromUpdateRequest(UpdateCategoryRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
        );
    }
}
