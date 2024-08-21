<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;

final readonly class ConsultVehicleByLicensePlateOutputDto
{
    public function __construct(
        public ?string      $manufacturer,
        public ?string      $color,
        public ?string      $model,
        public ?string      $licensePlate,
        public array        $pending,
        public Notification $notification
    ) {
    }
}
