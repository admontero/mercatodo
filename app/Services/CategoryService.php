<?php

namespace App\Services;

use App\DataTransferObjects\CategoryDTO;
use App\Models\Category;

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
