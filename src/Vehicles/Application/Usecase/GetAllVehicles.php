<?php

namespace Src\Vehicles\Application\Usecase;

use Exception;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\GetAllVehiclesOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

final readonly class GetAllVehicles
{
    public function __construct(
        public IVehicleRepository $vehicleRepository,
        public Notification       $notification,
    ) {
    }

    public function execute(): GetAllVehiclesOutputDto
    {
        try {
            $vehicles = $this->vehicleRepository->getAll();

            if (!is_null($vehicles) && $vehicles->isEmpty()) {
                $this->notification->addError([
                    'context' => 'get_all_vehicle',
                    'message' => 'Não possui veículos!',
                ]);

                return new GetAllVehiclesOutputDto($vehicles, $this->notification);
            }

            return new GetAllVehiclesOutputDto($vehicles, $this->notification);
        } catch (Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new GetAllVehiclesOutputDto(null, $this->notification);
        }
    }
}
