<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogoutEmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status'   => $this->getStatus(),
            'errors'   => $this->getErrors(),
            'message'  => $this->getMessage(),
            'employee' => $this->getEmployeeDetails(),
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
        return empty($this->employee) ? null : $this->employee->name().' deslogado!';
    }

    private function getEmployeeDetails()
    {
        if (empty($this->employee)) {
            return [];
        }

        return [
            'id'    => $this->employee->id(),
            'token' => $this->employee->token(),
            'name'  => $this->employee->name()->value(),
            'email' => $this->employee->email()->value(),
            'tipo'  => $this->employee->type()->value(),
        ];
    }
}
