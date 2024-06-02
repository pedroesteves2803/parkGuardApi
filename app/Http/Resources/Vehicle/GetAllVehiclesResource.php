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
                'manufacturer'   => $vehicle->manufacturer->value(),
                'color'          => $vehicle->color->value(),
                'model'          => $vehicle->model->value(),
                'licensePlate'   => $vehicle->licensePlate->value(),
                'entryTimes'     => $vehicle->entryTimes->value()->format('d-m-Y H:i:s'),
                'departureTimes' => !is_null($vehicle->departureTimes->value()) ? $vehicle->departureTimes->value()->format('d-m-Y H:i:s') : null,
            ];
        });

        return $vehicles;
    }
}
