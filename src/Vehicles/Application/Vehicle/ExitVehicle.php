<?php

namespace Src\Vehicles\Application\Vehicle;

use Exception;
use RuntimeException;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

final readonly class ExitVehicle
{
    public function __construct(
        private IVehicleRepository $vehicleRepository ,
        private Notification       $notification,
        private ExistVehicleById   $existVehicleById
    ) {
    }

    public function execute(ExitVehicleInputDto $input): ExitVehicleOutputDto
    {
        try {
            $vehicleExists = $this->existVehicleById->execute(
                new ExitVehicleInputDto($input->licensePlate)
            );

            if (!$vehicleExists->exist) {
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
}
