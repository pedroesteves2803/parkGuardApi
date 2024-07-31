<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class UpdateVehicleInputDto
{
    public function __construct(
        readonly string $id,
        readonly ?string $manufacturer,
        readonly ?string $color,
        readonly ?string $model,
        readonly string $licensePlate,
    ) {
    }
}
