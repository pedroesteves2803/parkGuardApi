<?php

namespace Src\Vehicles\Application\Vehicle;

use Exception;
use Illuminate\Support\Collection;
use RuntimeException;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetAllVehiclesOutputDto;
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

            if ($vehicles->isEmpty()) {
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
