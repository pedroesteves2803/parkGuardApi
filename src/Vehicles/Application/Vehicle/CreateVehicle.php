<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final class CreateVehicle
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(CreateVehicleInputDto $input): CreateVehicleOutputDto
    {
        $existVehicle = $this->resolveExistVehicle($input->licensePlate);

        if ($existVehicle instanceof Notification) {
            return new CreateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $vehicle = $this->iVehicleRepository->create(
            new Vehicle(
                null,
                new Manufacturer($input->manufacturer),
                new Color($input->color),
                new Model($input->model),
                new LicensePlate($input->licensePlate),
                new EntryTimes(
                    $input->entryTimes
                ),
                null
            )
        );

        return new CreateVehicleOutputDto(
            $vehicle,
            $this->notification
        );
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
