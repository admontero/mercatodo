<?php

namespace App\ApiAdmin\Product\Controllers;

use App\ApiAdmin\Product\Jobs\ProductImportJob;
use App\Controller;
use Domain\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ProductService $service): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv|max:2048',
        ]);

        $path = $request->file->storeAS('imports', 'products.csv');

        dispatch(new ProductImportJob($path, $request->user(), $service));

        return response()->json(['message' => 'Import Started'], 200);
    }
}
