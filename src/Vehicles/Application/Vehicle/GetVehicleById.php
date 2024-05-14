<?php

namespace Src\Vehicles\Application\Vehicle;

use DateTime;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;

use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final class GetVehicleById
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(GetVehicleInputDto $input): GetVehicleOutputDto
    {
        $vehicle = $this->resolveVehicleById($input->id);

        if ($vehicle instanceof Notification) {
            return new GetVehicleOutputDto(
                null,
                $this->notification
            );
        }

        return new GetVehicleOutputDto(
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
}
TypeError: Src\Vehicles\Domain\ValueObjects\DepartureTimes::value(): Return value must be of type DateTime, null returned in file C:\Users\pedroae.APCDADM\Documents\Code\parkGuardApi\src\Vehicles\Domain\ValueObjects\DepartureTimes.php on line 16
