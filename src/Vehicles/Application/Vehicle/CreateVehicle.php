<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Exception;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExistVehicleInputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Factory\VehicleFactory;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;


final readonly class CreateVehicle
{
    public function __construct(
        private IVehicleRepository              $vehicleRepository,
        private ConsultPendingByLicensePlate    $consultPendingByLicensePlate,
        private ISendPendingNotificationService $sendPendingNotificationService,
        private ExistVehicleById                $existVehicleById,
        private Notification                    $notification,
        private VehicleFactory                 $vehicleFactory
    ) {
    }

    public function execute(CreateVehicleInputDto $input): CreateVehicleOutputDto
    {
        try {
            $resultExistVehicleById = $this->existVehicleById->execute(
                new ExistVehicleInputDto($input->licensePlate)
            );

            if ($resultExistVehicleById->exist) {
                return new CreateVehicleOutputDto(null, $resultExistVehicleById->notification);
            }

            $consultOutputDto = $this->getPendingForLicensePlate($input->licensePlate);

            $vehicleEntity = $this->vehicleFactory->createWithPendings(
                null,
                $consultOutputDto->manufacturer,
                $consultOutputDto->color,
                $consultOutputDto->model,
                $consultOutputDto->licensePlate,
                new DateTime(),
                null,
                $consultOutputDto->pending
            );

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
}
