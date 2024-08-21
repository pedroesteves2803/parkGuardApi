<?php

namespace Src\Vehicles\Domain\Repositories\Dtos;

final readonly class IConsultVehicleRepositoryOutputDto
{
    public function __construct(
        public ?string $manufacturer,
        public ?string $color,
        public ?string $model,
        public ?string $licensePlate,
        public array   $pending,
    ) {
    }
}
