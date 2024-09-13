<?php

namespace Src\Vehicles\Application\Dtos;

use Src\Shared\Utils\Notification;

class ExistVehicleOutputDto
{
    public function __construct(
        public bool $exist,
        public Notification $notification
    ) {
    }
}
