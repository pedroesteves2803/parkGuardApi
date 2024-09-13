<?php

namespace Src\Vehicles\Application\Usecase;

use DateTime;
use Exception;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Factory\VehicleFactory;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;


final readonly class CreateVehicle
{
    public function __construct(
        private IVehicleRepository              $vehicleRepository,
        private ConsultPendingByLicensePlate    $consultPendingByLicensePlate,
        private ISendPendingNotificationService $sendPendingNotificationService,
        private Notification                    $notification,
        private VehicleFactory                 $vehicleFactory
    ) {
    }

    public function execute(CreateVehicleInputDto $input): CreateVehicleOutputDto
    {
        try {
            if ($this->vehicleExists($input->licensePlate)) {

                $this->notification->addError([
                    'context' => 'create_vehicle',
                    'message' => 'Placa já cadastrada!',
                ]);

                return new CreateVehicleOutputDto(null, $this->notification);
            }

            $resultFetPendingForLicensePlate = $this->getPendingForLicensePlate($input->licensePlate);

            $vehicleEntity = $this->createEntityVehicle($resultFetPendingForLicensePlate);

            if ($vehicleEntity->hasRestrictions()) {
                $this->sendPendingNotificationService->execute($vehicleEntity);
            }

            $vehicle = $this->saveVehicle($vehicleEntity);

            return new CreateVehicleOutputDto($vehicle, $this->notification);
        } catch (Exception $e) {
            $this->notification->addError([
                'context' => 'create_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new CreateVehicleOutputDto(null, $this->notification);
        }
    }

    private function getPendingForLicensePlate(string $licensePlate): ConsultVehicleByLicensePlateOutputDto
    {
        $consultInputDto = new ConsultVehicleByLicensePlateInputDto($licensePlate);
        return $this->consultPendingByLicensePlate->execute($consultInputDto);
    }

    private function saveVehicle(Vehicle $vehicle): Vehicle
    {
        return $this->vehicleRepository->create($vehicle);
    }

    private function vehicleExists(string $licensePlate):bool
    {
        return $this->vehicleRepository->existVehicle(
            new licensePlate($licensePlate)
        );
    }

    private function createEntityVehicle(ConsultVehicleByLicensePlateOutputDto $consultOutputDto): Vehicle
    {
        return $this->vehicleFactory->createWithPendings(
            null,
            $consultOutputDto->manufacturer,
            $consultOutputDto->color,
            $consultOutputDto->model,
            $consultOutputDto->licensePlate,
            new DateTime(),
            null,
            $consultOutputDto->pending
        );
    }
}
