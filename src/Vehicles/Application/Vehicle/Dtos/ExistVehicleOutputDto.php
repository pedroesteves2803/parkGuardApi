<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

class ExistVehicleOutputDto
{
    public function __construct(
        public bool $exist,
        public Notification $notification
    ) {
    }
}
