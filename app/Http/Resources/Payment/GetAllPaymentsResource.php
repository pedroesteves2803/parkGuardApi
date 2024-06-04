<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllPaymentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'payments' => $this->getPaymentsDetails(),
        ];
    }

    private function getStatus()
    {
        return ! $this->notification->hasErrors();
    }

    private function getErrors()
    {
        return $this->notification->getErrors();
    }

    private function getMessage()
    {
        return empty($this->payments) ? null : 'Lista de pagamentos encontrado!';
    }

    private function getPaymentsDetails()
    {
        if (empty($this->payments)) {
            return [];
        }

        $payments = $this->payments->map(function ($payment) {
            return [
                'id' => $payment->id(),
                'value' => is_null($payment->value()) ? null : $payment->value()->value(),
                'dateTime' => is_null($payment->registrationTime()) ? null : $payment->registrationTime()->value()->format('d-m-Y H:i:s'),
                'paymentMethod' => is_null($payment->paymentMethod()) ? null : $payment->paymentMethod()->value(),
                'paid' => $payment->paid(),
                'vehicle_id' => is_null($payment->vehicle()) ? null : $payment->vehicle()->id(),
            ];
        });

        return $payments;
    }
}
