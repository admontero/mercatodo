<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:120',
            'code' => 'required|string|max:30|unique:products,code',
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => 'nullable|numeric|gte:0',
            'image' => 'required|image|dimensions:min_width=640,min_height=480|max:2048',
            'category_id' => 'required|numeric|exists:categories,id',
        ];
    }
}
