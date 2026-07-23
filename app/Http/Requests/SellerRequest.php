<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerRequest extends FormRequest
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
    * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:sellers,name,' . $this->route('seller'),
            'surname' => 'required|string|max:50',
            'direction' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'sex' => 'nullable|string|size:1',
            'status' => 'required|string',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'identity_card' => 'required|string|max:20',
            'cart_id' => 'required|exists:carts,id|unique:sellers,cart_id,' . $this->route('seller'),
        ];
    }
}
