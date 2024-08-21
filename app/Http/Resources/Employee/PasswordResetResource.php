<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasswordResetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'employee' => $this->getEmployeeDetails(),
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

    private function getMessage(): ?string
    {
        return empty($this->employee) ? null : 'senha atualizada!';
    }

    private function getEmployeeDetails(): array
    {
        if (empty($this->employee)) {
            return [];
        }

        return [
            'id' => $this->employee->id(),
            'name' => $this->employee->name()->value(),
            'email' => $this->employee->email()->value(),
            'type' => $this->employee->type()->value(),
        ];
    }
}
