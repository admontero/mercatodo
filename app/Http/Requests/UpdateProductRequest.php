<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:120',
            'code' => ['required', 'string', 'max:30', Rule::unique('products', 'code')->ignore($this->product->id),],
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric',
            'image' => 'nullable|image|dimensions:min_width=640,min_height=480',
            'category_id' => 'required|numeric|exists:categories,id',
        ];
    }
}
