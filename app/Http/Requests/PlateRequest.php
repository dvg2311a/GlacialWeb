<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlateRequest extends FormRequest
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
            'code_plate' => 'required|string|max:50|unique:plates,code_plate,' . $this->route('plate'),
            'plate_dimension_id' => 'required|exists:plate_dimensions,id',
            'freezer_id' => 'required|exists:freezers,id',
        ];
    }
}
