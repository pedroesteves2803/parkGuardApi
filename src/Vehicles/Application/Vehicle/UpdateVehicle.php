<?php

namespace Src\Vehicles\Application\Vehicle;

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final readonly class UpdateVehicle
{
    public function __construct(
        public IVehicleRepository $iVehicleRepository,
        public Notification       $notification,
    ) {
    }

    public function execute(UpdateVehicleInputDto $input): UpdateVehicleOutputDto
    {
        try {
            $this->getVehicleById($input->id);

            $vehicle = $this->iVehicleRepository->update(
                new Vehicle(
                    $input->id,
                    !is_null($input->manufacturer) ? new Manufacturer($input->manufacturer) : null,
                    !is_null($input->color) ? new Color($input->color) : null,
                    !is_null($input->model) ? new Model($input->model) : null,
                    new LicensePlate($input->licensePlate),
                    new EntryTimes(new \DateTime()),
                    null
                )
            );

            return new UpdateVehicleOutputDto($vehicle, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'update_vehicle',
                'message' => $e->getMessage(),
            ]);

            return new UpdateVehicleOutputDto(null, $this->notification);
        }
    }

    private function getVehicleById(int $id): void
    {
        $vehicle = $this->iVehicleRepository->getById($id);

        if (is_null($vehicle)) {
            throw new \RuntimeException('Veiculo não encontrado!');
        }
    }
}
