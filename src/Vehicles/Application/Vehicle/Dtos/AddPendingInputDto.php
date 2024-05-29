<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use DateTime;
use Src\Vehicles\Domain\Entities\Vehicle;

final class AddPendingInputDto
{
    public function __construct(
        readonly int $id,
        readonly ?string $manufacturer,
        readonly ?string $color,
        readonly ?string $model,
        readonly string $licensePlate,
        readonly DateTime $entryTimes,
        readonly ?DateTime $departureTimes,
        readonly array $pendings
    ) {
    }
}
