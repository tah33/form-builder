<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class CustomFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'json_data'     => 'required_without:file|nullable|json',
            'file'          => 'required_without:json_data|nullable|file|mimes:json|max:2048',
        ];
    }
}
