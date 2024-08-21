<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final readonly class ExitVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
