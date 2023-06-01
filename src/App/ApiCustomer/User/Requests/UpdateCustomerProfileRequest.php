<?php

namespace App\ApiCustomer\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerProfileRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:80'],
            'document_type' => ['nullable', 'string', 'required_with:document'],
            'document' => ['nullable', 'string', 'max:30', 'required_with:document_type'],
            'country_id' => ['nullable', 'numeric', 'exists:countries,id'],
            'state_id' => ['nullable', 'numeric', 'exists:states,id'],
            'city_id' => ['nullable', 'numeric', 'exists:cities,id'],
            'address' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cell_phone' => ['nullable', 'string', 'max:25'],
        ];
    }
}
