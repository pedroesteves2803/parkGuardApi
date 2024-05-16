<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final class UpdateVehicle
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(UpdateVehicleInputDto $input): UpdateVehicleOutputDto
    {
        $resolveVehicleById = $this->resolveVehicleById($input->id);

        if ($resolveVehicleById instanceof Notification) {
            return new UpdateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $existVehicle = $this->resolveExistVehicle($input->licensePlate);

        if ($existVehicle instanceof Notification) {
            return new UpdateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $vehicle = $this->iVehicleRepository->update(
            new Vehicle(
                $input->id,
                new Manufacturer($input->manufacturer),
                new Color($input->color),
                new Model($input->model),
                new LicensePlate($input->licensePlate),
                new EntryTimes(new DateTime()),
                null
            )
        );

        return new UpdateVehicleOutputDto(
            $vehicle,
            $this->notification
        );
    }

    private function resolveVehicleById(int $id): Vehicle|Notification
    {
        $vehicle = $this->iVehicleRepository->getById($id);

        if (is_null($vehicle)) {
            return $this->notification->addError([
                'context' => 'vehicle_not_found',
                'message' => 'Veiculo não encontrado!',
            ]);
        }

        return $vehicle;
    }

    private function resolveExistVehicle(string $licensePlate): bool|Notification
    {
        $existVehicle = $this->iVehicleRepository->existVehicle(
            new LicensePlate($licensePlate)
        );

        if ($existVehicle) {
            return $this->notification->addError([
                'context' => 'license_plate_already_exists',
                'message' => 'Placa já cadastrado!',
            ]);
        }

        return $existVehicle;
    }
}
