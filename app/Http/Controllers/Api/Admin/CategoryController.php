<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
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

        $dataVal = $request->validated();

        $category = Category::create($dataVal);

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

        $dataVal = $request->validated();

        $category->update($dataVal);

        return response()->json(new CategoryResource($category), 201);
    }
}
