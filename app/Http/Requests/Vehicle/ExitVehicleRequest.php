<?php

namespace App\Http\Requests\vehicle;

use Illuminate\Foundation\Http\FormRequest;

class ExitVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'licensePlate' => 'required|string',
        ];
    }
}
