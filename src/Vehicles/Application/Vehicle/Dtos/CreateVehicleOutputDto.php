<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

final class CreateVehicleOutputDto
{
    public function __construct(
        readonly ?Vehicle $vehicle,
        readonly Notification $notification
    ) {
    }
}
