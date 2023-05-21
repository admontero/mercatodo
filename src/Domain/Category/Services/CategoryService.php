<?php

namespace Domain\Category\Services;

use Domain\Category\DTOs\CategoryDTO;
use Domain\Category\Models\Category;

class CategoryService
{
    public function store(CategoryDTO $dto): Category
    {
        return Category::create([
            'name' => $dto->name,
        ]);
    }

    public function update(CategoryDTO $dto, Category $category): Category
    {
        $category->update([
            'name' => $dto->name,
        ]);

        return $category;
    }
}
