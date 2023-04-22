<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\QueryFilters\CategoryFilter;
use App\QueryFilters\NameFilter;
use App\QueryFilters\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Product::with('category');

        $items = app(Pipeline::class)
            ->send($query)
            ->through([
                NameFilter::class,
                CategoryFilter::class,
                ProductOrder::class,
            ])
            ->thenReturn()
            ->paginate(15);

        $products = new ProductCollection($items);

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
