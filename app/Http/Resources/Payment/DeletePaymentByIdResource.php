<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeletePaymentByIdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'payment' => null,
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
        return 'Pagamento removido !';
    }
}
