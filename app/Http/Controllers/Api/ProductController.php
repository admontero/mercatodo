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
        return new ProductCollection(
            app(Pipeline::class)
            ->send(Product::with('category')->active())
            ->through([
                NameFilter::class,
                CategoryFilter::class,
                PriceFilter::class,
                ProductOrder::class,
            ])
            ->thenReturn()
            ->paginate(15)
        );
    }
}
