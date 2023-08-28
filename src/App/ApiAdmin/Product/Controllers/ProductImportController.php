<?php

namespace App\ApiAdmin\Product\Controllers;

use App\ApiAdmin\Product\Jobs\ProductImportJob;
use App\ApiAdmin\Product\Requests\ImportProductRequest;
use App\Controller;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ImportProductRequest $request, ProductService $service): JsonResponse
    {
        $this->authorize('import', new Product());

        $path = $request->file->storeAS('imports', 'products.csv');

        dispatch(new ProductImportJob($path, $request->user(), $service));

        return response()->json(['message' => 'Import Started'], 200);
    }
}
