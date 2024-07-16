<?php

namespace App\Http\Requests\payment;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paymentMethod' => 'required|integer',
            'vehicleId' => 'required|integer',
        ];
    }
}
