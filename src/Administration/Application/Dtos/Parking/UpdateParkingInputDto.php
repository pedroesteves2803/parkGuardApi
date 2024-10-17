<?php

namespace Src\Administration\Application\Dtos\Parking;

final readonly  class UpdateParkingInputDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $responsibleIdentification,
        public string $responsibleName,
        public float $pricePerHour,
        public float $additionalHourPrice
    ) {
    }
}
