<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

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
            'morning_checkup' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'evening_checkup' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'seller_id' => 'nullable|exists:sellers,id',
            'seller_reports' => 'nullable|array',
            'seller_reports.*.seller_id' => 'required|exists:sellers,id',
            'seller_reports.*.morning_checkup' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'seller_reports.*.evening_checkup' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
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

    #[Override]
    public function messages()
    {
        return [
            'report_date.required' => 'La fecha del reporte es obligatoria.',
            'report_date.date' => 'La fecha del reporte debe ser una fecha válida.',
            'report_date.before_or_equal' => 'La fecha del reporte no puede ser posterior a la fecha actual.',
            'morning_checkup.regex' => 'El formato de la hora de revisión matutina es inválido. Debe ser HH:MM o HH:MM:SS.',
            'evening_checkup.regex' => 'El formato de la hora de revisión vespertina es inválido. Debe ser HH:MM o HH:MM:SS.',
            'seller_id.exists' => 'El vendedor seleccionado no existe.',
            'seller_reports.array' => 'Los reportes de vendedores deben ser un arreglo.',
            'seller_reports.*.seller_id.required' => 'El vendedor es obligatorio.',
            'seller_reports.*.seller_id.exists' => 'El vendedor seleccionado no existe.',
            'seller_reports.*.morning_checkup.required' => 'La hora de revisión matutina es obligatoria.',
            'seller_reports.*.morning_checkup.regex' => 'El formato de la hora de revisión matutina es inválido para cada vendedor. Debe ser HH:MM o HH:MM:SS.',
            'seller_reports.*.evening_checkup.required' => 'La hora de revisión vespertina es obligatoria.',
            'seller_reports.*.evening_checkup.regex' => 'El formato de la hora de revisión vespertina es inválido para cada vendedor. Debe ser HH:MM o HH:MM:SS.',
            'seller_reports.*.products.array' => 'Los productos de cada vendedor deben ser un arreglo.',
            'seller_reports.*.products.min' => 'Cada vendedor debe tener al menos un producto.',
            'seller_reports.*.products.*.id.required' => 'El producto es obligatorio para cada producto.',
            'seller_reports.*.products.*.id.exists' => 'El producto seleccionado no existe.',
            'seller_reports.*.products.*.quantity_out.required' => 'La cantidad de salida es obligatoria para cada producto.',
            'seller_reports.*.products.*.quantity_out.integer' => 'La cantidad de salida debe ser un número entero para cada producto.',
            'seller_reports.*.products.*.quantity_out.min' => 'La cantidad de salida no puede ser negativa para cada producto.',
            'seller_reports.*.products.*.quantity_return.required' => 'La cantidad devuelta es obligatoria para cada producto.',
            'seller_reports.*.products.*.quantity_return.integer' => 'La cantidad devuelta debe ser un número entero para cada producto.',
            'seller_reports.*.products.*.quantity_return.min' => 'La cantidad devuelta no puede ser negativa para cada producto.',
            'seller_reports.*.products.*.wholesale_price.required' => 'El precio al por mayor es obligatorio para cada producto.',
            'seller_reports.*.products.*.wholesale_price.numeric' => 'El precio al por mayor debe ser un número para cada producto.',
            'seller_reports.*.products.*.wholesale_price.min' => 'El precio al por mayor no puede ser negativo para cada producto.',
            'seller_daily_reports.array' => 'Los reportes diarios de vendedores deben ser un arreglo.',
            'seller_daily_reports.*.id.required' => 'El ID del producto es obligatorio para cada producto en los reportes diarios.',
            'seller_daily_reports.*.id.exists' => 'El producto seleccionado no existe en los reportes diarios.',
            'seller_daily_reports.*.quantity_out.required' => 'La cantidad de salida es obligatoria para cada producto en los reportes diarios.',
            'seller_daily_reports.*.quantity_out.integer' => 'La cantidad de salida debe ser un número entero para cada producto en los reportes diarios.',
            'seller_daily_reports.*.quantity_out.min' => 'La cantidad de salida no puede ser negativa para cada producto en los reportes diarios.',
            'seller_daily_reports.*.quantity_return.required' => 'La cantidad devuelta es obligatoria para cada producto en los reportes diarios.',
            'seller_daily_reports.*.quantity_return.integer' => 'La cantidad devuelta debe ser un número entero para cada producto en los reportes diarios.',
            'seller_daily_reports.*.quantity_return.min' => 'La cantidad devuelta no puede ser negativa para cada producto en los reportes diarios.',
            'seller_daily_reports.*.wholesale_price.required' => 'El precio al por mayor es obligatorio para cada producto en los reportes diarios.',
            'seller_daily_reports.*.wholesale_price.numeric' => 'El precio al por mayor debe ser un número para cada producto en los reportes diarios.',
            'seller_daily_reports.* .wholesale_price.min' => 'El precio al por mayor no puede ser negativo para cada producto en los reportes diarios.',
        ];
    }
}
