<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class CreateVehicleInputDto
{
    public function __construct(
        readonly string $licensePlate,
    ) {
    }
}
