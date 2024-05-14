<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use DateTime;

final class GetVehicleInputDto
{
    public function __construct(
        readonly int $id,
    ) {
    }
}
