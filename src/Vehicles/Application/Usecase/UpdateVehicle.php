<?php

namespace Src\Vehicles\Application\Usecase;

use DateTime;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Dtos\UpdateVehicleOutputDto;
use Src\Vehicles\Domain\Factory\VehicleFactory;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

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
