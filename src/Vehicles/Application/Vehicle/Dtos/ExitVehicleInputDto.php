<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class ExitVehicleInputDto
{
    public function __construct(
        readonly string $licensePlate,
    ) {
    }
}
