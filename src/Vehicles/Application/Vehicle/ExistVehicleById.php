<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExistVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExistVehicleOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

readonly class ExistVehicleById
{
    public function __construct(
        public IVehicleRepository $vehicleRepository,
        public Notification       $notification,
    )
    {}

    public function execute(ExistVehicleInputDto $existVehicleInputDto): ExistVehicleOutputDto
    {
        $exists = $this->vehicleRepository->existVehicle(new LicensePlate($existVehicleInputDto->licensePlate));

        if ($exists) {
            $this->notification->addError([
                'context' => 'exist_vehicle_by_id',
                'message' => 'Placa já cadastrada!',
            ]);

            return  new ExistVehicleOutputDto(
                true,
                $this->notification
            );
        }

        return  new ExistVehicleOutputDto(
            false,
            $this->notification
        );
    }
}
