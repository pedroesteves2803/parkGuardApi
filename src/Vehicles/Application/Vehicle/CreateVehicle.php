<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Exception;
use RuntimeException;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExistVehicleInputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final readonly class CreateVehicle
{
    public function __construct(
        private IVehicleRepository              $vehicleRepository,
        private ConsultPendingByLicensePlate    $consultPendingByLicensePlate,
        private ISendPendingNotificationService $sendPendingNotificationService,
        private ExistVehicleById                $existVehicleById,
        private Notification                    $notification,
    ) {
    }

    public function execute(CreateVehicleInputDto $input): CreateVehicleOutputDto
    {
        try {

            $resultExistVehicleById = $this->existVehicleById->execute(
                new ExistVehicleInputDto(
                    $input->licensePlate
                )
            );

            if($resultExistVehicleById->exist){
                return new CreateVehicleOutputDto(null, $resultExistVehicleById->notification);
            }

            $consultOutputDto = $this->getPendingForLicensePlate($input->licensePlate);

            $vehicleEntity = $this->createVehicleEntity($consultOutputDto);

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

        $consultOutputDto = $this->consultPendingByLicensePlate->execute($consultInputDto);

        $hasRestriction = ! empty(array_filter($consultOutputDto->pending, static function (Pending $pending) {
            return !is_null($pending->description) && $pending->description->value() !== 'SEM RESTRICAO';
        }));

        if (!$hasRestriction) {
            $this->sendPendingNotificationService->execute(
                $this->createVehicleEntity($consultOutputDto)
            );
        }

        return $consultOutputDto;
    }

    private function createVehicleEntity(ConsultVehicleByLicensePlateOutputDto $consultOutputDto): Vehicle
    {
        $vehicle = new Vehicle(
            null,
            is_null($consultOutputDto->manufacturer) ? $consultOutputDto->manufacturer : new Manufacturer($consultOutputDto->manufacturer),
            is_null($consultOutputDto->color) ? $consultOutputDto->color : new Color($consultOutputDto->color),
            is_null($consultOutputDto->model) ? $consultOutputDto->model : new Model($consultOutputDto->model),
            new LicensePlate($consultOutputDto->licensePlate),
            new EntryTimes(new DateTime()),
            null
        );

        foreach ($consultOutputDto->pending as $pending) {
            $vehicle->addPending($pending);
        }

        return $vehicle;
    }

    private function saveVehicle(Vehicle $vehicle): Vehicle
    {
        return $this->vehicleRepository->create(
            $vehicle
        );
    }
}
