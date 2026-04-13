<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlateDimensionRequest extends FormRequest
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
            'width' => 'required|numeric|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'height' => 'required|numeric|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'unit_measure' => 'required|in:CM,INCH',
            'shape' => 'required|string|max:50',
        ];
    }
}
