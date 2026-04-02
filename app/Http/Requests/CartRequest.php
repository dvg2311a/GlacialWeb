<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plate_number' => 'required|string|max:20|unique:carts,plate_number,' . $this->route('cart'),
            'description' => 'nullable|string|max:50',
            'proprietary_type' => 'required|in:Company,Individual',
            'type_cart_id' => 'required|exists:type_carts,id',
            'status_cart_id' => 'required|exists:status_carts,id',
        ];
    }
}
