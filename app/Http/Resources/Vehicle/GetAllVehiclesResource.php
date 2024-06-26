<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="GetAllVehiclesResource",
 *     description="Resource representation for retrieving all vehicles.",
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
 *         property="vehicles",
 *         type="array",
 *         description="List of vehicles found.",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the vehicle"),
 *             @OA\Property(property="manufacturer", type="string", description="Manufacturer of the vehicle"),
 *             @OA\Property(property="color", type="string", description="Color of the vehicle"),
 *             @OA\Property(property="model", type="string", description="Model of the vehicle"),
 *             @OA\Property(property="licensePlate", type="string", description="License plate of the vehicle"),
 *             @OA\Property(property="entryTimes", type="string", format="date-time", description="Entry time of the vehicle"),
 *             @OA\Property(property="departureTimes", type="string", format="date-time", description="Departure time of the vehicle, if applicable")
 *         )
 *     )
 * )
 */
class GetAllVehiclesResource extends JsonResource
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
            'vehicles' => $this->getVehiclesDetails(),
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
        return empty($this->vehicles) ? null : 'Lista de veículos encontrados!';
    }

    /**
     * Get the details of the found vehicles.
     *
     * @return array
     */
    private function getVehiclesDetails(): array
    {
        if (empty($this->vehicles)) {
            return [];
        }

        $vehicles = $this->vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id(),
                'manufacturer' => $vehicle->manufacturer() ? $vehicle->manufacturer()->value() : null,
                'color' => $vehicle->color() ? $vehicle->color()->value() : null,
                'model' => $vehicle->model() ? $vehicle->model()->value() : null,
                'licensePlate' => $vehicle->licensePlate()->value(),
                'entryTimes' => $vehicle->entryTimes()->value()->format('d-m-Y H:i:s'),
                'departureTimes' => $vehicle->departureTimes()->value() ? $vehicle->departureTimes()->value()->format('d-m-Y H:i:s') : null,
            ];
        });

        return $vehicles->all();
    }
}
