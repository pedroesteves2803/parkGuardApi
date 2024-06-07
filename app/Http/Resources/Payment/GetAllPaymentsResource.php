<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="GetAllPaymentsResource",
 *     type="object",
 *     title="Get All Payments Resource",
 *     description="Resource returned when retrieving all payments",
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
 *         property="payments",
 *         type="array",
 *         description="List of payments",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="ID of the payment"
 *             ),
 *             @OA\Property(
 *                 property="value",
 *                 type="number",
 *                 format="float",
 *                 description="Value of the payment"
 *             ),
 *             @OA\Property(
 *                 property="dateTime",
 *                 type="string",
 *                 format="date-time",
 *                 description="Date and time when the payment was registered"
 *             ),
 *             @OA\Property(
 *                 property="paymentMethod",
 *                 type="string",
 *                 description="Method used for the payment"
 *             ),
 *             @OA\Property(
 *                 property="paid",
 *                 type="boolean",
 *                 description="Indicates if the payment has been made"
 *             ),
 *             @OA\Property(
 *                 property="vehicle_id",
 *                 type="integer",
 *                 description="ID of the vehicle associated with the payment"
 *             )
 *         )
 *     )
 * )
 */
class GetAllPaymentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'payments' => $this->getPaymentsDetails(),
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
        return empty($this->payments) ? null : 'Lista de pagamentos encontrada!';
    }

    private function getPaymentsDetails()
    {
        if (empty($this->payments)) {
            return [];
        }

        $payments = $this->payments->map(function ($payment) {
            return [
                'id' => $payment->id(),
                'value' => is_null($payment->value()) ? null : $payment->value()->value(),
                'dateTime' => is_null($payment->registrationTime()) ? null : $payment->registrationTime()->value()->format('d-m-Y H:i:s'),
                'paymentMethod' => is_null($payment->paymentMethod()) ? null : $payment->paymentMethod()->value(),
                'paid' => $payment->paid(),
                'vehicle_id' => is_null($payment->vehicle()) ? null : $payment->vehicle()->id(),
            ];
        });

        return $payments->toArray();
    }
}
