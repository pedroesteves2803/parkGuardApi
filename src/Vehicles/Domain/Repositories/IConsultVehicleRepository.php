<?php

namespace Src\Vehicles\Domain\Repositories;

use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

interface IConsultVehicleRepository
{
    public function consult(LicensePlate $licensePlate): ?Vehicle;
}
