<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Customer */
class CustomerResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'document_type' => $this->document_type,
            'document' => $this->document,
            'address' => $this->address,
            'phone' => $this->phone,
            'cell_phone' => $this->cell_phone,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
