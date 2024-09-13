<?php

namespace Src\Vehicles\Application\Dtos;

final readonly class ExitVehicleInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
