<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
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
        readonly ConsultPendingByLicensePlate $consultPendingByLicensePlate,
        readonly AddPending $addPending,
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

        $consultOutputDto = $this->resolveConsultPendingByLicensePlate($input);

        if ($consultOutputDto instanceof Notification) {
            return new CreateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $vehicle = $this->iVehicleRepository->create(
            new Vehicle(
                null,
                new Manufacturer($consultOutputDto->manufacturer),
                new Color($consultOutputDto->color),
                new Model($consultOutputDto->model),
                new LicensePlate($consultOutputDto->licensePlate),
                new EntryTimes(
                    new DateTime()
                ),
                null
            )
        );

        $this->resolveAddPendingsToVehicle($vehicle, $consultOutputDto);

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
                'message' => 'Placa já cadastrada!',
            ]);
        }

        return $existVehicle;
    }

    private function resolveAddPendingsToVehicle(Vehicle $vehicle, ConsultVehicleByLicensePlateOutputDto $consultOutputDto): void
    {
        $consultOutputDto->pendings->each(function (Pending $pending) use ($vehicle) {
            $vehicle->addPending(
                $pending
            );
        });

        $addPendingInputDto = new AddPendingInputDto(
            $vehicle,
        );

        $this->addPending->execute($addPendingInputDto);
    }

    private function resolveConsultPendingByLicensePlate(CreateVehicleInputDto $input): ConsultVehicleByLicensePlateOutputDto|Notification
    {
        $consultInputDto = new ConsultVehicleByLicensePlateInputDto($input->licensePlate);
        $consultOutputDto = $this->consultPendingByLicensePlate->execute($consultInputDto);

        $hasRestriction = $consultOutputDto->pendings->contains(function ($pending) {
            return $pending->description->value() !== 'SEM RESTRICAO';
        });

        if ($hasRestriction) {
            return $this->notification->addError([
                'context' => 'license_plate_already_exists',
                'message' => 'Veiculo com restrição!',
            ]);
        }

        return $consultOutputDto;
    }
}
