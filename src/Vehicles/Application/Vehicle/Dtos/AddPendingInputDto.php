<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Vehicles\Domain\Entities\Vehicle;

final class AddPendingInputDto
{
    public function __construct(
        readonly Vehicle $vehicle,
        readonly string $type,
        readonly string $description,
    ) {
    }
}
