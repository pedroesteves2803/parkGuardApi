<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final class GetAllVehiclesOutputDto
{
    public function __construct(
        readonly ?Collection $vehicles,
        readonly Notification $notification
    ) {
    }
}
