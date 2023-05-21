<?php

namespace App\Api\Product\Controllers;

use App\Controller;
use Domain\Product\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPriceRangeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'min_price' => Product::select('price')->active()->orderBy('price')->first()->price ?? null,
            'max_price' => Product::select('price')->active()->orderBy('price', 'DESC')->first()->price ?? null,
        ]);
    }
}
