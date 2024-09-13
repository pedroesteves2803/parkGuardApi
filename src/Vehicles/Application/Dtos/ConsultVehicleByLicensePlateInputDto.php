<?php

namespace Src\Vehicles\Application\Dtos;

final readonly class ConsultVehicleByLicensePlateInputDto
{
    public function __construct(
        public string $licensePlate,
    ) {
    }
}
