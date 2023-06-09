<?php

namespace App\ApiCustomer\Order\Resources;

use App\ApiAdmin\Product\Resources\ProductResource;
use App\ApiAdmin\User\Resources\CustomerResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Domain\Order\Models\Order */
class OrderResource extends JsonResource
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
            'code' => $this->code,
            'total' => $this->total,
            'provider' => $this->provider,
            'request_id' => $this->request_id,
            'url' => $this->url,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'user' => CustomerResource::make($this->whenLoaded('user')),
            'state' => (string) $this->state,
            'date' => Carbon::parse($this->created_at)->isoFormat('D \d\e MMMM \d\e YYYY'),
        ];
    }
}
