<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Vehicle;

final class ConsultVehicleByLicensePlateOutputDto
{
    public function __construct(
        readonly ?Vehicle $vehicle,
        readonly Notification $notification
    ) {
    }
}
