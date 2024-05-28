<?php

namespace Src\Vehicles\Application\Vehicle;

use phpDocumentor\Reflection\Types\Void_;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

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
        $consultOutputDto = $this->resolveConsultPendingByLicensePlate($input);

        if ($consultOutputDto instanceof Notification) {
            return new CreateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $existVehicle = $this->resolveExistVehicle($input->licensePlate);

        if ($existVehicle instanceof Notification) {
            return new CreateVehicleOutputDto(
                null,
                $this->notification
            );
        }

        $vehicle = $this->iVehicleRepository->create(
            $consultOutputDto->vehicle
        );

        $this->resolveAddPendingsToVehicle($vehicle);

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

    private function resolveAddPendingsToVehicle(Vehicle $vehicle): void
    {
        $vehicle->pendings()->map(function (Pending $pending) use ($vehicle) {
            $addPendingInputDto = new AddPendingInputDto(
                $vehicle,
                $pending->type->value(),
                $pending->description->value()
            );

            $this->addPending->execute($addPendingInputDto);
        });
    }

    private function resolveConsultPendingByLicensePlate(CreateVehicleInputDto $input): ConsultVehicleByLicensePlateOutputDto|Notification
    {
        $consultInputDto = new ConsultVehicleByLicensePlateInputDto($input->licensePlate);
        $consultOutputDto = $this->consultPendingByLicensePlate->execute($consultInputDto);

        if (is_null($consultOutputDto->vehicle)) {
            return $this->notification->addError([
                'context' => 'license_plate_already_exists',
                'message' => 'Placa já cadastrada!',
            ]);
        }

        return $consultOutputDto;
    }
}
