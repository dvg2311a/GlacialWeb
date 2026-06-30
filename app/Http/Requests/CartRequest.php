<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'plate_number' => ['required','string', 'max:20', Rule::unique('carts', 'plate_number')->ignore($this->route('cart'))],
            'description' => 'nullable|string|max:50',
            'proprietary_type' => 'required|in:Company,Individual',
            'type_cart_id' => 'required|exists:type_carts,id',
            'status_cart_id' => 'required|exists:status_carts,id',
        ];
    }
}
