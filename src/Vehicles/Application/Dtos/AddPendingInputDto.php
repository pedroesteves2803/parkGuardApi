<?php

namespace Src\Vehicles\Application\Dtos;

use DateTime;

final readonly class AddPendingInputDto
{
    public function __construct(
        public int       $id,
        public ?string   $manufacturer,
        public ?string   $color,
        public ?string   $model,
        public string    $licensePlate,
        public DateTime  $entryTimes,
        public ?DateTime $departureTimes,
        public array     $pendings
    ) {
    }
}
