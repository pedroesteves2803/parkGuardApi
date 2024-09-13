<?php

namespace Src\Vehicles\Application\Dtos;

final readonly class GetVehicleInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
