<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Consult;

final class ConsultVehicleByLicensePlateOutputDto
{
    public function __construct(
        readonly ?Consult $consult,
        readonly Notification $notification
    ) {
    }
}
