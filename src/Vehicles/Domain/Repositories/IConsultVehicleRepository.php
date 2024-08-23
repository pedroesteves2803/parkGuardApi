<?php

namespace Src\Vehicles\Domain\Repositories;

use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

interface IConsultVehicleRepository
{
    public function consult(LicensePlate $licensePlate): IConsultVehicleRepositoryOutputDto;
}
