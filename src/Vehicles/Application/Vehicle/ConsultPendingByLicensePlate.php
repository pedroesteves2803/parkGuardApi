<?php

namespace Src\Vehicles\Application\Vehicle;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

final class ConsultPendingByLicensePlate
{
    public function __construct(
        readonly IConsultVehicleRepository $iConsultVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(ConsultVehicleByLicensePlateInputDto $input): ConsultVehicleByLicensePlateOutputDto
    {
        $outputResolveConsultVehicle = $this->resolveConsultVehicle($input->licensePlate);

        return new ConsultVehicleByLicensePlateOutputDto(
            $outputResolveConsultVehicle->manufacturer,
            $outputResolveConsultVehicle->color,
            $outputResolveConsultVehicle->model,
            $outputResolveConsultVehicle->licensePlate,
            $outputResolveConsultVehicle->pendings,
            $this->notification
        );
    }

    public function resolveConsultVehicle(string $licensePlate)
    {
        $outputResolveConsultVehicle = $this->iConsultVehicleRepository->consult(
            new LicensePlate($licensePlate)
        );

        return $outputResolveConsultVehicle;
    }
}
