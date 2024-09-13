<?php

namespace Src\Vehicles\Application\Dtos;

final readonly class UpdateVehicleInputDto
{
    public function __construct(
        public string  $id,
        public ?string $manufacturer,
        public ?string $color,
        public ?string $model,
        public string  $licensePlate,
    ) {
    }
}
