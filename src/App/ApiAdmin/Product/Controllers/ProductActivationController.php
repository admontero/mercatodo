<?php

namespace App\ApiAdmin\Product\Controllers;

use App\Controller;
use Domain\Product\Models\Product;
use Domain\Product\States\Activated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductActivationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update-status', $product);

        $product->state->transitionTo(Activated::class);

        return response()->json(['state' => (string) $product->state]);
    }
}
