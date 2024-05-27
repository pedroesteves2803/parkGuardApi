<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Type;

final class AddPending
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(AddPendingInputDto $input): AddPendingOutputDto
    {
        $existVehicle = $this->resolveExistVehicle($input->vehicle->licensePlate);

        if ($existVehicle instanceof Notification) {

            return new AddPendingOutputDto(
                $this->notification
            );
        }

        $input->vehicle->consult()->addPending(
            new Pending(
                null,
                new Type($input->type),
                new Description($input->description)
            )
        );

        $this->iVehicleRepository->addPending(
            $input->vehicle
        );

        return new AddPendingOutputDto(
            $this->notification
        );
    }

    private function resolveExistVehicle(string $licensePlate): bool|Notification
    {
        $existVehicle = $this->iVehicleRepository->existVehicle(
            new LicensePlate($licensePlate)
        );

        if (! $existVehicle) {
            return $this->notification->addError([
                'context' => 'license_plate_already_exists',
                'message' => 'Veiculo não cadastrado!',
            ]);
        }

        return $existVehicle;
    }
}
