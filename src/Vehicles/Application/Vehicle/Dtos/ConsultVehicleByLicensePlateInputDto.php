<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final readonly class ConsultVehicleByLicensePlateInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
