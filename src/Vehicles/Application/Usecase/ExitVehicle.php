<?php

namespace Src\Vehicles\Application\Usecase;

use Exception;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

final readonly class ExitVehicle
{
    public function __construct(
        private IVehicleRepository $vehicleRepository ,
        private Notification       $notification,
    ) {
    }

    public function execute(ExitVehicleInputDto $input): ExitVehicleOutputDto
    {
        try {
            if (! $this->vehicleExists($input->licensePlate)) {
                $this->notification->addError([
                    'context' => 'exit_vehicle',
                    'message' => 'Veículo não encontrado!',
                ]);

                return new ExitVehicleOutputDto(null, $this->notification);
            }

            $vehicle = $this->vehicleRepository->exit(
                new LicensePlate($input->licensePlate),
            );

            return new ExitVehicleOutputDto($vehicle, $this->notification);

        } catch (Exception $e) {
            $this->notification->addError([
                'context' => 'exit_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new ExitVehicleOutputDto(null, $this->notification);
        }
    }

    private function vehicleExists(string $licensePlate):bool
    {
        return $this->vehicleRepository->existVehicle(
            new licensePlate($licensePlate)
        );
    }
}
