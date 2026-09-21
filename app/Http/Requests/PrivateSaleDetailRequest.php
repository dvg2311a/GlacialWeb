<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PrivateSaleDetailRequest extends FormRequest
{

    public function authorize(): bool
    {
        return \true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|required|min:1',
            'unit_price' => 'required|decimal:0,2',
            'line_total' => 'required|decimal:0,2',
            'type_price' => 'required|in:Detalle,Mayorista,Malo',

            'user_id' => 'nullable|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'private_sale_id' => 'required|exists:private_sales,id'
        ];
    }
}
