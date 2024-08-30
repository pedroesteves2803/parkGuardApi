<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

readonly class ExistVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
