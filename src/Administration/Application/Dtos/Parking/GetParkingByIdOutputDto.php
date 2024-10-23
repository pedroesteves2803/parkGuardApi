<?php

namespace Src\Administration\Application\Dtos\Parking;

use Src\Administration\Domain\Entities\Parking;
use Src\Shared\Utils\Notification;

final readonly class GetParkingByIdOutputDto
{
    public function __construct(
        public ?Parking $parking,
        public Notification $notification
    ) {
    }
}
