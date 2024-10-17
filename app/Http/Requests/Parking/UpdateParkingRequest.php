<?php

namespace App\Http\Requests\Parking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParkingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'responsibleIdentification' => 'required|string',
            'responsibleName' => 'required|string',
            'pricePerHour' => 'required',
            'additionalHourPrice' => 'required',
        ];
    }
}
