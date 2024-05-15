<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final class GetVehicleInputDto
{
    public function __construct(
        readonly int $id,
    ) {
    }
}
