<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifyTokenPasswordResetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'token' => $this->getTokenDetails(),
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
        return empty($this->passwordResetToken) ? null : 'Token validado!';
    }

    private function getTokenDetails()
    {
        if (empty($this->passwordResetToken)) {
            return [];
        }

        return [
            'email' => $this->passwordResetToken->email()->value(),
            'token' => $this->passwordResetToken->token()->value(),
            'expirationTime' => $this->passwordResetToken->expirationTime()->value(),
        ];
    }
}
