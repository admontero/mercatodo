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
            'first_name' => $this->profileable->first_name ?? null,
            'last_name' => $this->profileable->last_name ?? null,
            'document_type' => $this->profileable->document_type ?? null,
            'document' => $this->profileable->document ?? null,
            'address' => $this->profileable->address ?? null,
            'phone' => $this->profileable->phone ?? null,
            'cell_phone' => $this->profileable->cell_phone ?? null,
            'ago' => $this->created_at->diffForHumans(),
        ];
    }
}
