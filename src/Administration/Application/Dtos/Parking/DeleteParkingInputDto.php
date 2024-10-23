<?php

namespace Src\Administration\Application\Dtos\Parking;

class DeleteParkingInputDto
{
    public function __construct(
        public int $parkingId,
    ) {
    }
}
