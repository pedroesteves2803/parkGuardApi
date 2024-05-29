<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Vehicle;

final class ConsultVehicleByLicensePlateOutputDto
{
    public function __construct(
        readonly ?string $manufacturer,
        readonly ?string $color,
        readonly ?string $model,
        readonly ?string $licensePlate,
        readonly array $pendings,
        readonly Notification $notification
    ) {
    }
}
