<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
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
            'email' => $this->email,
            'status' => (string) $this->status,
            'first_name' => $this->profileable?->first_name,
            'last_name' => $this->profileable?->last_name,
            'document_type' => $this->profileable?->document_type,
            'document' => $this->profileable?->document,
            'address' => $this->profileable?->address,
            'phone' => $this->profileable?->phone,
            'cell_phone' => $this->profileable?->cell_phone,
            'ago' => $this->created_at->diffForHumans(),
        ];
    }
}
