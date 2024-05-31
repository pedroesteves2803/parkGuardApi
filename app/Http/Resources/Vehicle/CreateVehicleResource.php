<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateVehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status'  => $this->getStatus(),
            'errors'  => $this->getErrors(),
            'message' => $this->getMessage(),
            'vehicle' => $this->getVehicleDetails(),
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
        return empty($this->vehicle) ? null : $this->vehicle->licensePlate.' registrado!';
    }

    private function getVehicleDetails()
    {
        if (empty($this->vehicle)) {
            return [];
        }

        return [
            'id'             => $this->vehicle->id,
            'manufacturer'   => is_null($this->vehicle->manufacturer) ? null : $this->vehicle->manufacturer->value(),
            'color'          => is_null($this->vehicle->color) ? null : $this->vehicle->color->value(),
            'model'          => is_null($this->vehicle->model) ? null : $this->vehicle->model->value(),
            'licensePlate'   => $this->vehicle->licensePlate->value(),
            'entryTimes'     => $this->vehicle->entryTimes->value()->format('Y-m-d H:i:s'),
            'departureTimes' => is_null($this->vehicle->departureTimes) ? null : $this->vehicle->departureTimes->value(),
        ];
    }
}
