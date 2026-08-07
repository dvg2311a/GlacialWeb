<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerDailyReportRequest extends FormRequest
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
            'report_date' => 'required|date',
            'morning_checkup' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'evening_checkup' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'seller_id' => 'nullable|exists:sellers,id',
            'seller_reports' => 'nullable|array',
            'seller_reports.*.seller_id' => 'required|exists:sellers,id',
            'seller_reports.*.products' => 'nullable|array|min:1',
            'seller_reports.*.products.*.id' => 'required|exists:products,id',
            'seller_reports.*.products.*.quantity_out' => 'required|integer|min:0',
            'seller_reports.*.products.*.quantity_return' => 'required|integer|min:0',
            'seller_reports.*.products.*.wholesale_price' => 'required|numeric|min:0',
            'seller_daily_reports' => 'nullable|array',
            'seller_daily_reports.*.id' => 'required|exists:products,id',
            'seller_daily_reports.*.quantity_out' => 'required|integer|min:0',
            'seller_daily_reports.*.quantity_return' => 'required|integer|min:0',
            'seller_daily_reports.*.wholesale_price' => 'required|numeric|min:0',
        ];
    }
}
