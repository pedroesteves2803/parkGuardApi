<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

final readonly class GetVehicleInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
