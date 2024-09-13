<?php

namespace Src\Vehicles\Application\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

final readonly class GetVehicleOutputDto
{
    public function __construct(
        public ?Vehicle     $vehicle,
        public Notification $notification
    ) {
    }
}
