<?php

namespace Src\Administration\Application\Dtos\Parking;

final readonly class GetParkingByIdInputDto
{
    public function __construct(
        public int $parkingId,
    ) {
    }
}
