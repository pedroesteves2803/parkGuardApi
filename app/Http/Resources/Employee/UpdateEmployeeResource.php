<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UpdateEmployeeResource",
 *     type="object",
 *     title="Update Employee Response",
 *     description="Response schema for updating an employee",
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="Indicates whether the operation was successful"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         description="List of errors, if any",
 *         @OA\Items(
 *             type="string"
 *         )
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Success message if the operation was successful"
 *     ),
 *     @OA\Property(
 *         property="employee",
 *         type="object",
 *         description="Details of the updated employee",
 *         @OA\Property(
 *             property="id",
 *             type="string",
 *             description="ID of the employee"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             description="Name of the employee"
 *         ),
 *         @OA\Property(
 *             property="email",
 *             type="string",
 *             format="email",
 *             description="Email address of the employee"
 *         ),
 *         @OA\Property(
 *             property="tipo",
 *             type="string",
 *             description="Type of employee"
 *         )
 *     )
 * )
 */
class UpdateEmployeeResource extends JsonResource
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
        return empty($this->employee) ? null : $this->employee->name().' atualizado!';
    }

    private function getEmployeeDetails()
    {
        if (empty($this->employee)) {
            return [];
        }

        return [
            'id'    => $this->employee->id(),
            'name'  => $this->employee->name()->value(),
            'email' => $this->employee->email()->value(),
            'tipo'  => $this->employee->type()->value(),
        ];
    }
}
