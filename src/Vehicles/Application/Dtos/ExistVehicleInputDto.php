<?php

namespace Src\Vehicles\Application\Dtos;

readonly class ExistVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
