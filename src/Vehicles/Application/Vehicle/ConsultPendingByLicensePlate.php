<?php

namespace Src\Vehicles\Application\Vehicle;

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
        $vehicleData = $this->resolveConsultVehicle($input->licensePlate);

        if ($vehicleData instanceof Notification) {
            return new ConsultVehicleByLicensePlateOutputDto(
                null,
                $this->notification
            );
        }

        return new ConsultVehicleByLicensePlateOutputDto(
            $vehicleData,
            $this->notification
        );
    }

    public function resolveConsultVehicle(string $licensePlate)
    {
        $vehicleData = $this->iConsultVehicleRepository->consult(
            new LicensePlate($licensePlate)
        );

        if (! $vehicleData) {
            return $this->notification->addError([
                'context' => 'vehicle_data_not_found',
                'message' => 'Dados do veiculo não encontrado!',
            ]);
        }

        return $vehicleData;
    }
}
