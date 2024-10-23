<?php

namespace App\Http\Resources\Parking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllParkingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'    => $this->getStatus(),
            'errors'    => $this->getErrors(),
            'message'   => $this->getMessage(),
            'employees' => $this->getParkingsDetails(),
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
        return empty($this->parkings) ? null : 'Lista de estacionamentos encontrados!';
    }

    private function getParkingsDetails(): array
    {
        if (empty($this->parkings)) {
            return [];
        }

        return $this->parkings->map(function ($item) {
            return [
                'id' => $item->id(),
                'responsible_identification' => $item->responsibleIdentification(),
                'responsible_name' => $item->responsibleName()->value(),
                'price_per_hour' => $item->pricePerHour()->value(),
                'additional_hour_price' => $item->additionalHourPrice()->value(),
            ];
        })->toArray();
    }
}
