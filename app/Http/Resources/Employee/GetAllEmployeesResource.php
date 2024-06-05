<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="GetAllEmployeesResource",
 *     title="Get All Employees Resource",
 *     description="Resource for returning a list of employees",
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
 *         property="employees",
 *         type="array",
 *         description="List of employees",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", description="Employee ID"),
 *             @OA\Property(property="name", type="string", description="Employee name"),
 *             @OA\Property(property="email", type="string", description="Employee email"),
 *             @OA\Property(property="tipo", type="integer", description="Employee type")
 *         )
 *     )
 * )
 */
class GetAllEmployeesResource extends JsonResource
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
        return empty($this->employees) ? null : 'Lista de funcionarios encontrados!';
    }

    private function getEmployeesDetails()
    {
        if (empty($this->employees)) {
            return [];
        }

        $employees = $this->employees->map(function ($item) {
            return [
                'id'    => $item->id(),
                'name'  => $item->name()->value(),
                'email' => $item->email()->value(),
                'tipo'  => $item->type()->value(),
            ];
        });

        return $employees;
    }
}
