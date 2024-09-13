<?php

namespace Src\Vehicles\Application\Dtos;

final readonly class CreateVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
