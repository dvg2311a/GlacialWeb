<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderEnterpriseRequest extends FormRequest
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
            'order_date' => 'required|date',
            'expected_date' => 'required|date|after:order_date',
            'order_status' => 'required|in:Pending,Received',
            'total_tax' => 'required|decimal:0,2',
            'subtotal_base' => 'required|decimal:0,2',
            'grand_total' => 'required|decimal:0,2',
        ];
    }
}
