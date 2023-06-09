<?php

namespace App\ApiCustomer\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'provider' => 'required|string',
            'products' => 'required|string',
            'products.*.price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'products.*.quantity' => 'required|numeric|gt:0',
        ];
    }
}
