<?php

namespace App\Http\Resources\Parking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateParkingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->getStatus(),
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
            'parking' => $this->getParkingDetails(),
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
        return empty($this->parking) ? null : $this->parking->id().' atualizado!';
    }

    private function getParkingDetails(): array
    {
        if (empty($this->parking)) {
            return [];
        }

        return [
            'id' => $this->parking->id(),
            'responsible_identification' => $this->parking->responsibleIdentification(),
            'responsible_name' => $this->parking->responsibleName()->value(),
            'price_per_hour' => $this->parking->pricePerHour()->value(),
            'additional_hour_price' => $this->parking->additionalHourPrice()->value(),
        ];
    }
}
