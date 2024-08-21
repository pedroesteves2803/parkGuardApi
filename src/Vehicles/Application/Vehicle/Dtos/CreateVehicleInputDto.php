<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final readonly class CreateVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
