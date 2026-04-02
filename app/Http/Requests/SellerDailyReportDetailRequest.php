<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerDailyReportDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'quantity_out' => 'required|integer|min:0',
            'quantity_return' => 'required|integer|min:0',
            'quantity_sold' => 'required|integer|min:0',
            'total_sales' => 'required|decimal:0,2',
            'wholesale_price' => 'required|decimal:0,2',
            'seller_daily_report_id' => 'required|exists:seller_daily_reports,id',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
