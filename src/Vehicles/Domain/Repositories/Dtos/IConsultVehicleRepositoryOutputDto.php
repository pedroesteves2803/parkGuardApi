<?php

namespace Src\Vehicles\Domain\Repositories\Dtos;

final class IConsultVehicleRepositoryOutputDto
{
    public function __construct(
        readonly ?string $manufacturer,
        readonly ?string $color,
        readonly ?string $model,
        readonly ?string $licensePlate,
        readonly array $pendings,
    ) {
    }
}
