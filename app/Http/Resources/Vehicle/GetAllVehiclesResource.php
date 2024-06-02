<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllVehiclesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'   => $this->getStatus(),
            'errors'   => $this->getErrors(),
            'message'  => $this->getMessage(),
            'vehicles' => $this->getVehiclesDetails(),
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
        return empty($this->vehicles) ? null : 'Lista de veiculos encontrados!';
    }

    private function getVehiclesDetails()
    {
        if (empty($this->vehicles)) {
            return [];
        }

        $vehicles = $this->vehicles->map(function ($vehicle) {
            return [
                'id'             => $vehicle->id,
                'manufacturer'   => is_null($vehicle->manufacturer) ? null : $vehicle->manufacturer->value(),
                'color'          => is_null($vehicle->color) ? null : $vehicle->color->value(),
                'model'          => is_null($vehicle->model) ? null : $vehicle->model->value(),
                'licensePlate'   => $vehicle->licensePlate->value(),
                'entryTimes'     => $vehicle->entryTimes->value()->format('d-m-Y H:i:s'),
                'departureTimes' => is_null($vehicle->departureTimes) ? null : $vehicle->departureTimes->value(),
            ];
        });

        return $vehicles;
    }
}
