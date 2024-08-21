<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DeletePaymentByIdResource",
 *     type="object",
 *     title="Delete Payment By Id Resource",
 *     description="Resource returned when a payment is deleted",
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="Indicates if the operation was successful"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         @OA\Items(type="string"),
 *         description="List of errors, if any"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="A message describing the result of the operation"
 *     ),
 *     @OA\Property(
 *         property="payment",
 *         type="object",
 *         nullable=true,
 *         description="Details of the payment, which is null in this case"
 *     )
 * )
 */
class DeletePaymentByIdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'payment' => null,
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

    private function getMessage(): string
    {
        return 'Pagamento removido!';
    }
}
