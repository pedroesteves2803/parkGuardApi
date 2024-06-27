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
            'manufacturer' => 'required|string',
            'color' => 'required|string',
            'model' => 'required|string',
            'licensePlate' => 'required|string',
        ];
    }
}
