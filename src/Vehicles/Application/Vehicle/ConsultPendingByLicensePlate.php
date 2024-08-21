<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

final readonly class ConsultPendingByLicensePlate
{
    public function __construct(
        public IConsultVehicleRepository $consultVehicleRepository,
        public Notification              $notification,
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
            $outputResolveConsultVehicle->pending,
            $this->notification
        );
    }

    public function resolveConsultVehicle(string $licensePlate): IConsultVehicleRepositoryOutputDto
    {
        return $this->consultVehicleRepository->consult(
            new LicensePlate($licensePlate)
        );
    }
}
