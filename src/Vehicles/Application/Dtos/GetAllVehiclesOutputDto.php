<?php

namespace Src\Vehicles\Application\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final readonly class GetAllVehiclesOutputDto
{
    public function __construct(
        public ?Collection  $vehicles,
        public Notification $notification
    ) {
    }
}
