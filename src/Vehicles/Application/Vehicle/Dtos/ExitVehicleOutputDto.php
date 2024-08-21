<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

final readonly class ExitVehicleOutputDto
{
    public function __construct(
        public ?Vehicle     $vehicle,
        public Notification $notification
    ) {
    }
}
