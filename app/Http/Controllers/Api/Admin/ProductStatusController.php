<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update-status', $product);

        $product->changeStatus();

        return response()->json(['status' => (string) $product->status]);
    }
}
