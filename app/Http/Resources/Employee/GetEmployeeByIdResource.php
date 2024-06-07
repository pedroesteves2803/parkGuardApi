<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="GetEmployeeByIdResource",
 *     title="Get employee Resource",
 *     description="Recourse to return an employee",
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="Indicates if the request was successful or not"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         description="List of errors (if any)",
 *         @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Message indicating the status of the response"
 *     ),
 *     @OA\Property(
 *         property="employee",
 *         type="array",
 *         description="Eemployees",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", description="Employee ID"),
 *             @OA\Property(property="name", type="string", description="Employee name"),
 *             @OA\Property(property="email", type="string", description="Employee email"),
 *             @OA\Property(property="tipo", type="integer", description="Employee type")
 *         )
 *     )
 * )
 */
class GetEmployeeByIdResource extends JsonResource
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
        return empty($this->employee) ? null : $this->employee->name().' encontrado!';
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
