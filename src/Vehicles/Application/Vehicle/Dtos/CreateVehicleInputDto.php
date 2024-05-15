<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class CreateVehicleInputDto
{
    public function __construct(
        readonly string $manufacturer,
        readonly string $color,
        readonly string $model,
        readonly string $licensePlate,
        readonly \DateTime $entryTimes,
        readonly ?\DateTime $departureTimes,
    ) {
    }
}
