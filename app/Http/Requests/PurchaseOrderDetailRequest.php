<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderDetailRequest extends FormRequest
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
            'boxes_ordered' => 'required|integer|min:1',
            'boxes_received' => 'required|integer|min:0',
            'purchase_price_per_box' => 'required|decimal:0,2',
            'tax_percentage' => 'required|decimal:0,2',
            'tax_amount_per_box' => 'required|decimal:0,2',
            'purchase_price_total_per_box' => 'required|decimal:0,2',
            'order_enterprise_id' => 'required|exists:orders_enterprises,id',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
