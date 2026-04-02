<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FreezerRequest extends FormRequest
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
            'number_freezer' => 'required|string|max:2|unique:freezers,number_freezer,' . $this->route('freezer'),
            'status' => 'required|string',
            'type_freezer_id' => 'required|exists:type_freezers,id',
        ];
    }
}
