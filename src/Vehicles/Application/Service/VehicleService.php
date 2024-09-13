<?php

namespace Src\Vehicles\Application\Service;

use Src\Vehicles\Application\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Application\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Application\Usecase\ExitVehicle;
use Src\Vehicles\Application\Usecase\GetVehicleById;
use Src\Vehicles\Domain\Service\IVehicleService;

readonly class VehicleService implements IVehicleService
{
    public function __construct(
        private GetVehicleById $vehicleById,
        private ExitVehicle $exitVehicle
    )
    {}

    public function getVehicleById(int $vehicleId): GetVehicleOutputDto
    {
       return $this->vehicleById->execute(
            new GetVehicleInputDto($vehicleId)
        );
    }

    public function exitVehicle(string $licensePlate): ExitVehicleOutputDto
    {
        return  $this->exitVehicle->execute(
            new ExitVehicleInputDto($licensePlate)
        );
    }
}
