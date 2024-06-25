<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 *
 * @OA\Schema(
 *     schema="LoginEmployeeResource",
 *     type="object",
 *     title="LoginEmployeeResource",
 *     description="Resource para retornar os dados do login de um funcionário",
 *     @OA\Property(property="status", type="boolean", description="Status do login"),
 *     @OA\Property(property="errors", type="array", @OA\Items(type="string"), description="Lista de erros"),
 *     @OA\Property(property="message", type="string", nullable=true, description="Mensagem de sucesso"),
 *     @OA\Property(
 *         property="employee",
 *         type="object",
 *         @OA\Property(property="id", type="integer", description="ID do funcionário"),
 *         @OA\Property(property="token", type="string", description="Token de autenticação do funcionário"),
 *         @OA\Property(property="name", type="string", description="Nome do funcionário"),
 *         @OA\Property(property="email", type="string", description="Email do funcionário"),
 *         @OA\Property(property="tipo", type="string", description="Tipo do funcionário")
 *     )
 * )
 */
class LoginEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
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
        return empty($this->employee) ? null : $this->employee->name().' logado!';
    }

    private function getEmployeeDetails()
    {
        if (empty($this->employee)) {
            return [];
        }

        return [
            'id'    => $this->employee->id(),
            'token'    => $this->employee->token(),
            'name'  => $this->employee->name()->value(),
            'email' => $this->employee->email()->value(),
            'tipo'  => $this->employee->type()->value(),
        ];
    }
}
