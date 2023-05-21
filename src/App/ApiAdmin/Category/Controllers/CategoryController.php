<?php

namespace App\ApiAdmin\Category\Controllers;

use App\ApiAdmin\Category\Requests\StoreCategoryRequest;
use App\ApiAdmin\Category\Requests\UpdateCategoryRequest;
use App\ApiAdmin\Category\Resources\CategoryCollection;
use App\ApiAdmin\Category\Resources\CategoryResource;
use App\Controller;
use Domain\Category\DTOs\CategoryDTO;
use Domain\Category\Models\Category;
use Domain\Category\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): CategoryCollection
    {
        $this->authorize('view-any', new Category());

        $categories = new CategoryCollection(
            Category::latest()
                    ->select('id', 'name', 'slug', 'created_at')
                    ->paginate(10)
        );

        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', new Category());

        $category = $this->categoryService->store(CategoryDTO::fromStoreRequest($request));

        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $this->categoryService->update(CategoryDTO::fromUpdateRequest($request), $category);

        return response()->json(new CategoryResource($category), 201);
    }
}
