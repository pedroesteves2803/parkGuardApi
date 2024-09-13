<?php

namespace Src\Vehicles\Application\Usecase;

use Exception;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

final readonly class GetVehicleById
{
    public function __construct(
        private IVehicleRepository $vehicleRepository,
        private Notification       $notification,
    ) {
    }

    public function execute(GetVehicleInputDto $input): GetVehicleOutputDto
    {
        try {
            $vehicle = $this->vehicleRepository->getById($input->id);

            if (is_null($vehicle)){
                $this->notification->addError([
                    'context' => 'get_vehicle_by_id',
                    'message' => 'Veiculo não encontrado!',
                ]);

                return new GetVehicleOutputDto(null, $this->notification);
            }

            return new GetVehicleOutputDto($vehicle, $this->notification);
        } catch (Exception $e) {
            $this->notification->addError([
                'context' => 'get_vehicle_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetVehicleOutputDto(null, $this->notification);
        }
    }
}
