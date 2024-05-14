<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetVehicleByIdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'   => $this->getStatus(),
            'errors'   => $this->getErrors(),
            'message'  => $this->getMessage(),
            'employee' => $this->getVehicleDetails(),
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
        return empty($this->vehicle) ? null : $this->vehicle->licensePlate.' encontrado!';
    }

    private function getVehicleDetails()
    {
        if (empty($this->vehicle)) {
            return [];
        }

        return [
            'id'    => $this->vehicle->id,
            'manufacturer'  => $this->vehicle->manufacturer->value(),
            'color' => $this->vehicle->color->value(),
            'model'  => $this->vehicle->model->value(),
            'licensePlate'  => $this->vehicle->licensePlate->value(),
            'entryTimes'  => $this->vehicle->entryTimes->value()->format('Y-m-d H:i:s'),
            'departureTimes'  => !is_null($this->vehicle->departureTimes->value()) ? $this->vehicle->departureTimes->value()->format('Y-m-d H:i:s') : null,
        ];
    }
}
