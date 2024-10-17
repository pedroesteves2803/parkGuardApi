<?php

namespace Src\Administration\Application\Dtos\Parking;

final readonly class CreateParkingInputDto
{
    public function __construct(
        public string $name,
        public string $responsibleIdentification,
        public string $responsibleName,
        public float $pricePerHour,
        public float $additionalHourPrice
    ) {
    }
}
