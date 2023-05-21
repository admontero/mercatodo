<?php

namespace App\Api\Product\Controllers;

use App\ApiAdmin\Product\Resources\ProductCollection;
use App\Controller;
use Domain\Product\Models\Product;
use Domain\Product\QueryBuilders\CategoryQueryBuilder;
use Domain\Product\QueryBuilders\NameQueryBuilder;
use Domain\Product\QueryBuilders\PriceQueryBuilder;
use Domain\Product\QueryBuilders\ProductOrderQueryBuilder;
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
                NameQueryBuilder::class,
                CategoryQueryBuilder::class,
                PriceQueryBuilder::class,
                ProductOrderQueryBuilder::class,
            ])
            ->thenReturn()
            ->paginate(15)
        );
    }
}
