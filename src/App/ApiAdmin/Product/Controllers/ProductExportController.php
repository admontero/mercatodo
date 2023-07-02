<?php

namespace App\ApiAdmin\Product\Controllers;

use App\Controller;
use Domain\Product\Jobs\ProductExportJob;
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
        dispatch(new ProductExportJob($request->user(), $service));

        return response()->json(['message' => 'Export Completed'], 200);
    }
}
