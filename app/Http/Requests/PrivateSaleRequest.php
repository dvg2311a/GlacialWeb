<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PrivateSaleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_sale' => 'required|date',
            'grand_total' => 'required|decimal:0,2',
            'seller_id' => 'nullable|exists:sellers,id'
        ];
    }
}
