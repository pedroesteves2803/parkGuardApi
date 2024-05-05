<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "status" => $this->getStatus(),
            "errors" => $this->getErrors(),
            "message" => $this->getMessage(),
            "employee" => $this->getCategoryDetails()
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
        return empty($this->employee) ? null : $this->employee->name. " adicionado!";
    }

    private function getCategoryDetails()
    {

        if(empty($this->employee)){
            return [];
        }

        return [
            "id" => $this->employee->id,
            "name" => $this->employee->name->value(),
            "email" => $this->employee->email->value(),
            "tipo" => $this->employee->type->value(),
        ];
    }
}
