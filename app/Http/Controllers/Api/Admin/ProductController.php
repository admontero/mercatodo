<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
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
                ->select(['id', 'name', 'slug', 'code', 'price', 'stock', 'category_id', 'status', 'created_at'])
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

        $filename  = time() . '.' . $request->file('image')->getClientOriginalExtension();

        $image = $request->file('image')
            ->storeAs('products', $filename, 'public');

        Image::make(Storage::disk('public')->path($image))
            ->resize(640, 480, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->resizeCanvas(640, 480)
            ->save();

        $product = Product::create([
            'name' => $dataVal['name'],
            'code' => $dataVal['code'],
            'price' => $dataVal['price'],
            'stock' => $dataVal['stock'] ?? 0,
            'description' => $dataVal['description'] ?? null,
            'category_id' => $dataVal['category_id'],
            'image' => $image,
        ]);

        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json(new ProductResource($product), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', new Product());

        $dataVal = $request->validated();

        if ($request->file('image')) {

            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $filename  = time() . '.' . $request->file('image')->getClientOriginalExtension();

            $image = $request->file('image')
                ->storeAs('products', $filename, 'public');

            Image::make(Storage::disk('public')->path($image))
                ->resize(640, 480, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->resizeCanvas(640, 480)
                ->save();
        }

        $product->update([
            'name' => $dataVal['name'],
            'code' => $dataVal['code'],
            'price' => $dataVal['price'],
            'stock' => $dataVal['stock'] ?? 0,
            'description' => $dataVal['description'] ?? '',
            'category_id' => $dataVal['category_id'],
            'image' => isset($image) ? $image : $product->image,
        ]);

        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
