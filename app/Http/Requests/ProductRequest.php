<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:50|unique:products,name,' . $this->route('product'),
            'description' => 'required|string|max:100',
            'shape' => 'nullable|string|max:50',
            'wholesale_price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'bad_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'freezer_id' => 'required|exists:freezers,id',
            'type_product_id' => 'required|exists:type_products,id',
            'flavor_id' => 'required|exists:flavor_products,id',
            'status_product_id' => 'required|exists:status_products,id',
        ];
    }
}
