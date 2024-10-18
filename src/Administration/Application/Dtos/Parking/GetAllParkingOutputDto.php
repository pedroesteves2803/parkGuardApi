<?php

namespace Src\Administration\Application\Dtos\Parking;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final readonly class GetAllParkingOutputDto
{
    public function __construct(
        public ?Collection $parkings,
        public Notification $notification
    ) {
    }
}
