<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Factory\VehicleFactory;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final readonly class UpdateVehicle
{
    public function __construct(
        private IVehicleRepository $vehicleRepository,
        private Notification       $notification,
        private VehicleFactory     $vehicleFactory,

    ) {
    }

    public function execute(UpdateVehicleInputDto $input): UpdateVehicleOutputDto
    {
        try {
            $vehicle = $this->vehicleRepository->getById($input->id);

            if (is_null($vehicle)) {
                $this->notification->addError([
                    'context' => 'update_vehicle',
                    'message' => 'Veiculo não encontrado!',
                ]);

                return new UpdateVehicleOutputDto(null, $this->notification);
            }

            $vehicle = $this->vehicleRepository->update(
                $this->vehicleFactory->create(
                    $input->id,
                    $input->manufacturer,
                    $input->color,
                    $input->model,
                    $input->licensePlate,
                    new DateTime(),
                    null
                ),
            );

            return new UpdateVehicleOutputDto($vehicle, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'update_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new UpdateVehicleOutputDto(null, $this->notification);
        }
    }
}
