<?php

namespace Src\Administration\Application\Employee\Dtos;

use DateTime;

final class CreateVehicleInputDto
{
    public function __construct(
        readonly string $manufacturer,
        readonly string $color,
        readonly string $model,
        readonly string $licensePlate,
        readonly DateTime $entryTimes,
        readonly DateTime $departureTimes,
    ) {
    }
}
