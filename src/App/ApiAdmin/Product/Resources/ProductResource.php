<?php

namespace App\ApiAdmin\Product\Resources;

use App\ApiAdmin\Category\Resources\CategoryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Domain\Product\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'state' => (string) $this->state,
            'ago' => Carbon::parse($this->created_at)->diffForHumans(),
            'pivot' => $this->whenPivotLoaded('order_product', function () {
                return [
                    'price' => $this->getRelationValue('pivot')->price,
                    'quantity' => $this->getRelationValue('pivot')->quantity,
                ];
            }),
        ];
    }
}
