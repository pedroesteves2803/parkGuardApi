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
        try {

            $this->assertVehicleDoesNotExist($input->licensePlate);

            $vehicle = $this->iVehicleRepository->exit(
                new LicensePlate($input->licensePlate),
            );

            return new ExitVehicleOutputDto($vehicle, $this->notification);

        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'exit_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new ExitVehicleOutputDto(null, $this->notification);
        }
    }

    private function assertVehicleDoesNotExist(string $licensePlate): void
    {
        $existVehicle = $this->iVehicleRepository->existVehicle(
            new LicensePlate($licensePlate)
        );

        if (! $existVehicle) {
            throw new \Exception('Veiculo não encontrado!');
        }
    }
}
