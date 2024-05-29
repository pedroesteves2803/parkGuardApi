<?php

namespace Src\Vehicles\Application\Vehicle;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetAllVehiclesOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

final class GetAllVehicles
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(): GetAllVehiclesOutputDto
    {
        try {
            $vehicles = $this->getVehicles();

            return new GetAllVehiclesOutputDto($vehicles, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new GetAllVehiclesOutputDto(null, $this->notification);
        }
    }

    private function getVehicles(): Collection
    {
        $vehicles = $this->iVehicleRepository->getAll();

        if (is_null($vehicles)) {
            throw new \Exception('Não possui veiculos!');
        }

        return $vehicles;
    }
}
