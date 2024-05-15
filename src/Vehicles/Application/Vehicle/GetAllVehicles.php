<?php

namespace Src\Vehicles\Application\Vehicle;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetAllVehiclesOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleOutputDto;
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
        $vehicles = $this->resolveVehicles();

        if ($vehicles instanceof Notification) {
            return new GetVehicleOutputDto(
                null,
                $this->notification
            );
        }

        return new GetAllVehiclesOutputDto(
            $vehicles,
            $this->notification
        );
    }

    private function resolveVehicles(): Collection|Notification
    {
        $vehicles = $this->iVehicleRepository->getAll();

        if (is_null($vehicles)) {
            return $this->notification->addError([
                'context' => 'vehicles_not_found',
                'message' => 'Não possui veiculos!',
            ]);
        }

        return $vehicles;
    }
}
