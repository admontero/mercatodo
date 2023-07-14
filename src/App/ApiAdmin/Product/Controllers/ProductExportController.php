<?php

namespace App\ApiAdmin\Product\Controllers;

use App\ApiAdmin\Product\Jobs\ProductExportJob;
use App\Controller;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductExportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ProductService $service): JsonResponse
    {
        $this->authorize('export', new Product());

        dispatch(new ProductExportJob($request->user(), $service));

        return response()->json(['message' => 'Export Completed'], 200);
    }
}
