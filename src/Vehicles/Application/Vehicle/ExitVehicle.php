<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

final class ExitVehicle
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(ExitVehicleInputDto $input): ExitVehicleOutputDto
    {
        $existVehicle = $this->resolveExistVehicle($input->licensePlate);

        if ($existVehicle instanceof Notification) {
            return new ExitVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $vehicle = $this->iVehicleRepository->exit(
            new LicensePlate($input->licensePlate),
        );

        return new ExitVehicleOutputDto(
            $vehicle,
            $this->notification
        );
    }

    private function resolveExistVehicle(string $licensePlate): bool|Notification
    {
        $existVehicle = $this->iVehicleRepository->existVehicle(
            new LicensePlate($licensePlate)
        );

        if (!$existVehicle) {
            return $this->notification->addError([
                'context' => 'vehicle_not_found',
                'message' => 'Veiculo não encontrado!',
            ]);
        }

        return $existVehicle;
    }
}
