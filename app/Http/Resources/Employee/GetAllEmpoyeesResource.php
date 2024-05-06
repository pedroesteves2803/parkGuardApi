<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllEmpoyeesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'    => $this->getStatus(),
            'errors'    => $this->getErrors(),
            'message'   => $this->getMessage(),
            'employees' => $this->getEmployeesDetails(),
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
        return empty($this->employee) ? null : 'Lista de funcionarios encontrados!';
    }

    private function getEmployeesDetails()
    {
        if (empty($this->employee)) {
            return [];
        }

        $employees = $this->employee->map(function ($item) {
            return [
                'id'    => $item->id,
                'name'  => $item->name->value(),
                'email' => $item->email->value(),
                'tipo'  => $item->type->value(),
            ];
        });

        return $employees;
    }
}
