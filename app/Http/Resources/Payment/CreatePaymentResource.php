<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatePaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'  => $this->getStatus(),
            'errors'  => $this->getErrors(),
            'message' => $this->getMessage(),
            'payment' => $this->getPaymentDetails(),
        ];
    }

    private function getStatus()
    {
        return !$this->notification->hasErrors();
    }

    private function getErrors()
    {
        return $this->notification->getErrors();
    }

    private function getMessage()
    {
        return empty($this->payment) ? null : 'Pagamento com o id: '.$this->payment->id().' registrado!';
    }

    private function getPaymentDetails()
    {
        if (empty($this->payment)) {
            return [];
        }

        return [
            'id'            => $this->payment->id(),
            'value'         => is_null($this->payment->value()) ? null : $this->payment->value()->value(),
            'dateTime'      => is_null($this->payment->dateTime()) ? null : $this->payment->dateTime()->value()->format('d-m-Y H:i:s'),
            'paymentMethod' => is_null($this->payment->paymentMethod()) ? null : $this->payment->paymentMethod()->value(),
            'paid'          => $this->payment->paid(),
            'vehicle_id'    => is_null($this->payment->vehicle()) ? null : $this->payment->vehicle()->id(),
        ];
    }
}
