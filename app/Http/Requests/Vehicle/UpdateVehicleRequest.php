<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer' => 'nullable|string',
            'color' => 'nullable|string',
            'model' => 'nullable|string',
            'licensePlate' => 'required|string',
        ];
    }
}
