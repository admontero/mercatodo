<?php

namespace App\Services;

use App\DataTransferObjects\ProductDTO;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductService
{
    public function createProduct(ProductDTO $dto): Product
    {
        return Product::create([
            'name' => $dto->name,
            'code' => $dto->code,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'image' => $dto->image,
        ]);
    }

    public function updateProduct(ProductDTO $dto, Product $product): Product
    {
        $product->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'image' => $dto->image ?? $product->image,
        ]);

        return $product;
    }

    public function uploadImage(Request $request, Product $product = null): ?string
    {
        if ($request->hasFile('image')) {

            /** @var string $previousPath */
            $previousPath = $product->image ?? '';

            if (Storage::disk('public')->exists($previousPath)) {
                Storage::disk('public')->delete($previousPath);
            }

            $filename  = time() . '.' . $request->image->extension();

            $imagePath = $request->image->storeAs('products', $filename, 'public');

            Image::make(Storage::disk('public')->path($imagePath))
                ->resize(640, 480, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->resizeCanvas(640, 480)
                ->save();

            return $imagePath;
        }

        return null;
    }
}