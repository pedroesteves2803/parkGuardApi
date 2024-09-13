<?php

namespace Src\Vehicles\Domain\Service;


use Src\Vehicles\Application\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;

interface IVehicleService
{
    public function getVehicleById(int $vehicleId): GetVehicleOutputDto;

    public function exitVehicle(string $licensePlate): ExitVehicleOutputDto;
}
