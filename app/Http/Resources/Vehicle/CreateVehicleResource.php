<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="CreateVehicleResource",
 *     description="Resource representation for the creation of a vehicle.",
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="Status of the operation. True if successful, false otherwise."
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         description="Errors encountered during the operation, if any.",
 *         @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Message indicating the success of the operation, if applicable."
 *     ),
 *     @OA\Property(
 *         property="vehicle",
 *         type="object",
 *         description="Details of the created vehicle.",
 *         @OA\Property(property="id", type="integer", description="ID of the vehicle"),
 *         @OA\Property(property="manufacturer", type="string", description="Manufacturer of the vehicle"),
 *         @OA\Property(property="color", type="string", description="Color of the vehicle"),
 *         @OA\Property(property="model", type="string", description="Model of the vehicle"),
 *         @OA\Property(property="licensePlate", type="string", description="License plate of the vehicle"),
 *         @OA\Property(property="entryTimes", type="string", format="date-time", description="Entry time of the vehicle"),
 *         @OA\Property(property="departureTimes", type="string", format="date-time", description="Departure time of the vehicle, if applicable")
 *     )
 * )
 */
class CreateVehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'vehicle' => $this->getVehicleDetails(),
        ];
    }

    /**
     * Get the status of the operation.
     *
     * @return bool
     */
    private function getStatus(): bool
    {
        return !$this->notification->hasErrors();
    }

    /**
     * Get the errors from the notification.
     *
     * @return array|null
     */
    private function getErrors(): ?array
    {
        return $this->notification->hasErrors() ? $this->notification->getErrors() : null;
    }

    /**
     * Get the message indicating the success of the operation.
     *
     * @return string|null
     */
    private function getMessage(): ?string
    {
        return empty($this->vehicle) ? null : $this->vehicle->licensePlate()->value() . ' registrado!';
    }

    /**
     * Get the details of the created vehicle.
     *
     * @return array
     */
    private function getVehicleDetails(): array
    {
        if (empty($this->vehicle)) {
            return [];
        }

        return [
            'id' => $this->vehicle->id(),
            'manufacturer' => $this->vehicle->manufacturer() ? $this->vehicle->manufacturer()->value() : null,
            'color' => $this->vehicle->color() ? $this->vehicle->color()->value() : null,
            'model' => $this->vehicle->model() ? $this->vehicle->model()->value() : null,
            'licensePlate' => $this->vehicle->licensePlate()->value(),
            'entryTimes' => $this->vehicle->entryTimes()->value()->format('d-m-Y H:i:s'),
            'departureTimes' => $this->vehicle->departureTimes() ? $this->vehicle->departureTimes()->value() : null,
        ];
    }
}
