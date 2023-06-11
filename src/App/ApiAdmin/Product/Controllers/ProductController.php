<?php

namespace App\ApiAdmin\Product\Controllers;

use App\ApiAdmin\Product\Requests\StoreProductRequest;
use App\ApiAdmin\Product\Requests\UpdateProductRequest;
use App\ApiAdmin\Product\Resources\ProductCollection;
use App\ApiAdmin\Product\Resources\ProductResource;
use App\Controller;
use Domain\Product\DTOs\ProductDTO;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ProductCollection
    {
        $this->authorize('view-any', new Product());

        $products = new ProductCollection(
            Product::with('category:id,name,created_at')
                ->select(['id', 'name', 'slug', 'code', 'price', 'stock', 'category_id', 'state', 'created_at'])
                ->latest()
                ->paginate(10)
        );

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', new Product());

        $image = $this->service->uploadImage($request);

        $product = $this->service->createProduct(
            ProductDTO::fromStoreRequest($request, $image)
        );

        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json(new ProductResource($product), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', new Product());

        $image = $this->service->uploadImage($request);

        $product = $this->service->updateProduct(
            ProductDTO::fromUpdateRequest($request, $image),
            $product,
        );

        return response()->json(new ProductResource($product), 201);
    }
}
