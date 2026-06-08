<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TypeFreezerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30|min:3|unique:type_freezers,name,' . $this->route('type_freezer'),
            'description' => 'required|string|max:100|min:3',
        ];
    }
}
// Rule::unique('type_freezers', 'name')->ignore($this->route('type_freezer'))
