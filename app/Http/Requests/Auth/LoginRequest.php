<?php

namespace App\Http\Requests\Auth;

use App\Traits\ResponseFormatTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'     => 'required|string|email',
            'password'  => 'required|string',
        ];
    }
}
