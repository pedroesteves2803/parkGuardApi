<?php

namespace App\Http\Requests\employee;

use Illuminate\Foundation\Http\FormRequest;

class LoginEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ];
    }
}
