<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class ConsultVehicleByLicensePlateInputDto
{
    public function __construct(
        readonly string $licensePlate,
    ) {
    }
}