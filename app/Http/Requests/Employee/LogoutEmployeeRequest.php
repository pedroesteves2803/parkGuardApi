<?php

namespace App\Http\Requests\employee;

use Illuminate\Foundation\Http\FormRequest;

class LogoutEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string'
        ];
    }
}
