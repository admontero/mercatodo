<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = new ProductCollection(
            Product::with('category:id,name,created_at')
                ->select(['id', 'name', 'slug', 'code', 'price', 'category_id', 'status', 'created_at'])
                ->latest()
                ->paginate(10)
        );

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', new Product());

        $dataVal = $request->validated();

        $image = $request->file('image');

        $filename  = time() . '.' . $image->getClientOriginalExtension();

        $path = public_path('storage/products');

        if (!file_exists($path)) {
            mkdir($path, 777, true);
        }

        $imagePath = public_path('storage/products/' . $filename);

        Image::make($image->getRealPath())
            ->resize(640, 480, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->resizeCanvas(640, 480)
            ->save($imagePath);

        $product = Product::create([
            'name' => $dataVal['name'],
            'code' => $dataVal['code'],
            'price' => $dataVal['price'],
            'description' => $dataVal['description'] ?? null,
            'category_id' => $dataVal['category_id'],
            'image' => 'products/' . $filename,
        ]);

        return response()->json(new ProductResource($product), 201);
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
