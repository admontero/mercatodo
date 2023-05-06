<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Filters\CategoryFilter;
use App\Filters\NameFilter;
use App\Filters\PriceFilter;
use App\Filters\ProductOrder;
use Illuminate\Pipeline\Pipeline;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ProductCollection
    {
        $query = Product::with('category')->active();

        $items = app(Pipeline::class)
            ->send($query)
            ->through([
                NameFilter::class,
                CategoryFilter::class,
                PriceFilter::class,
                ProductOrder::class,
            ])
            ->thenReturn()
            ->paginate(15);

        $products = new ProductCollection($items);

        return $products;
    }
}
