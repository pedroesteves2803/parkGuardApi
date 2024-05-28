<?php

namespace Src\Vehicles\Domain\Repositories\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final class IConsultVehicleRepositoryOutputDto
{
    public function __construct(
        readonly ?string $manufacturer,
        readonly ?string $color,
        readonly ?string $model,
        readonly ?string $licensePlate,
        readonly Collection $pendings,
    ) {
    }
}
