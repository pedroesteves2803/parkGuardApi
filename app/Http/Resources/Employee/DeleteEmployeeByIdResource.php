<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DeleteEmployeeByIdResource",
 *     type="object",
 *     title="Delete Employee Response",
 *     description="Response schema for deleting an employee",
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
 *         type="null",
 *         description="Employee information, which is null in this case"
 *     )
 * )
 */
class DeleteEmployeeByIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status'   => $this->getStatus(),
            'errors'   => $this->getErrors(),
            'message'  => $this->getMessage(),
            'employee' => null,
        ];
    }

    private function getStatus(): bool
    {
        return !$this->notification->hasErrors();
    }

    private function getErrors()
    {
        return $this->notification->getErrors();
    }

    private function getMessage(): ?string
    {
        return $this->notification->hasErrors() ? null : 'Funcionario removido!';
    }
}
