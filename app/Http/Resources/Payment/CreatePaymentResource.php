<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CreatePaymentResource",
 *     type="object",
 *     title="Create Payment Resource",
 *     description="Resource returned when a payment is created",
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
 *         @OA\Property(
 *             property="id",
 *             type="integer",
 *             description="ID of the payment"
 *         ),
 *         @OA\Property(
 *             property="value",
 *             type="number",
 *             format="float",
 *             description="Value of the payment"
 *         ),
 *         @OA\Property(
 *             property="value_in_reais",
 *             type="number",
 *             format="float",
 *             description="Value of the payment in Brazilian reais"
 *         ),
 *         @OA\Property(
 *             property="dateTime",
 *             type="string",
 *             format="date-time",
 *             description="Date and time when the payment was registered"
 *         ),
 *         @OA\Property(
 *             property="paymentMethod",
 *             type="string",
 *             description="Method used for the payment"
 *         ),
 *         @OA\Property(
 *             property="paid",
 *             type="boolean",
 *             description="Indicates if the payment has been made"
 *         ),
 *         @OA\Property(
 *             property="vehicle_id",
 *             type="integer",
 *             description="ID of the vehicle associated with the payment"
 *         )
 *     )
 * )
 */
class CreatePaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'payment' => $this->getPaymentDetails(),
        ];
    }

    private function getStatus(): bool
    {
        return ! $this->notification->hasErrors();
    }

    private function getErrors()
    {
        return $this->notification->getErrors();
    }

    private function getMessage(): ?string
    {
        return empty($this->payment) ? null : 'Pagamento com o id: '.$this->payment->id().' registrado!';
    }

    private function getPaymentDetails(): array
    {
        if (empty($this->payment)) {
            return [];
        }

        return [
            'id' => $this->payment->id(),
            'value' => is_null($this->payment->value()) ? null : $this->payment->value()->value(),
            'value_in_reais' => is_null($this->payment->value()) ? null : $this->payment->value()->valueInReais(),
            'dateTime' => is_null($this->payment->registrationTime()) ? null : $this->payment->registrationTime()->value()->format('d-m-Y H:i:s'),
            'paymentMethod' => is_null($this->payment->paymentMethod()) ? null : $this->payment->paymentMethod()->value(),
            'paid' => $this->payment->paid(),
            'vehicle_id' => is_null($this->payment->vehicle()) ? null : $this->payment->vehicle()->id(),
        ];
    }
}
