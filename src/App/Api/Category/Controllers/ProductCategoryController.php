<?php

namespace App\Api\Category\Controllers;

use App\Controller;
use Domain\Category\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $categories = Category::select('id', 'name')->get();

        return response()->json($categories);
    }
}
