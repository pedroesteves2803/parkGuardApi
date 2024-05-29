<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

final class GetVehicleById
{
    public function __construct(
        readonly IVehicleRepository $iVehicleRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(GetVehicleInputDto $input): GetVehicleOutputDto
    {
        try {
            $vehicle = $this->getVehicleById($input->id);

            if ($vehicle instanceof Notification) {
                return new GetVehicleOutputDto(
                    null,
                    $this->notification
                );
            }

            return new GetVehicleOutputDto($vehicle, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_vehicle_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetVehicleOutputDto(null, $this->notification);
        }
    }

    private function getVehicleById(int $id): Vehicle
    {
        $vehicle = $this->iVehicleRepository->getById($id);

        if (is_null($vehicle)) {
            throw new \Exception('Veiculo não encontrado!');
        }

        return $vehicle;
    }
}
