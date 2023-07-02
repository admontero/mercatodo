<?php

namespace Domain\Product\Services;

use Domain\Category\Models\Category;
use Domain\Product\DTOs\ProductDTO;
use Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            $filename = time().'.'.$request->image->extension();

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

    public function checkStockAvailable(Product $product, int $qty): void
    {
        if ($product->stock < $qty) {
            Log::channel('placetopay')->info("[PAY]: Existencias insuficientes del producto {$product->code}");
            throw new \Exception('No hay existencias suficientes para crear la orden.', 500);
        }
    }

    public function getProductById(int $id): Product
    {
        $product = Product::find($id);

        if (! $product) {
            Log::channel('placetopay')->info('[PAY]: Producto de la orden no encontrado');
            throw new \Exception('El producto a comprar no existe.', 500);
        }

        return $product;
    }

    public function createExcelFile(): ?string
    {
        $headers = [
            'name',
            'code',
            'description',
            'price',
            'stock',
            'category',
        ];

        $fileName = 'exports/products.csv';

        $this->createFile($fileName);

        $file = $this->openFile($fileName, 'w');

        if (!$file) {
            throw new \Exception('Error al abrir el archivo.', 500);
        }

        fputcsv($file, $headers);

        Product::with('category')->chunk(100, function ($products) use ($file) {
            foreach ($products as $product) {
                fputcsv($file, [
                    'name' => $product->name,
                    'code' => $product->code,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category?->name,
                ]);
            }
        });

        fclose($file);

        return Storage::disk(config()->get('filesystem.default'))->path($fileName);
    }

    public function readExcelFile(string $pathFile): void
    {
        $file = $this->openFile($pathFile, 'r');

        if (! $file) {
            throw new \Exception('Error al abrir el archivo.', 500);
        }

        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $this->processRow($row);
        }

        fclose($file);
    }

    public function createFile(string $fileName): void
    {
        Storage::disk(config()->get('filesystem.default'))->put($fileName, '');
    }

    /**
     * @param string $fileName
     * @param string $mode
     * @return resource|false
     */
    public function openFile(string $fileName, string $mode)
    {
        return fopen(Storage::disk(config()->get('filesystem.default'))->path($fileName), $mode);
    }

    private const HEADERS = [
        'name' => 0,
        'code' => 1,
        'description' => 2,
        'price' => 3,
        'stock' => 4,
        'category' => 5,
    ];

    /** @param array<int, string> $row */
    private function processRow(array $row): void
    {
        Product::query()->updateOrCreate([
            'code' => trim($row[self::HEADERS['code']]),
        ], [
            'name' => trim($row[self::HEADERS['name']]),
            'description' => trim($row[self::HEADERS['description']]),
            'price' => trim($row[self::HEADERS['price']]),
            'stock' => trim($row[self::HEADERS['stock']]),
            'category_id' => $this->getCategoryId(trim($row[self::HEADERS['category']])),
        ]);
    }

    private function getCategoryId(string $categoryName): int
    {
        $category = Category::query()->firstOrCreate([
            'name' => $categoryName,
        ], [
            'name' => $categoryName,
        ]);

        return $category->id;
    }
}
