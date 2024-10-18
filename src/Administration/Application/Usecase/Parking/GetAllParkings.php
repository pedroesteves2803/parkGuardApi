<?php

namespace Src\Administration\Application\Usecase\Parking;

use Src\Administration\Application\Dtos\Parking\GetAllParkingOutputDto;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;

final readonly class GetAllParkings
{
    public function __construct(
        private IParkingRepository $parkingRepository,
        private Notification        $notification,
    ) {}

    public function execute(): GetAllParkingOutputDto
    {
        try {
            $employees = $this->parkingRepository->getAll();

            if (is_null($employees)) {
                $this->notification->addError([
                    'context' => 'get_all_parkings',
                    'message' => 'Não possui estacionamentos!',
                ]);

                return new GetAllParkingOutputDto(null, $this->notification);
            }

            return new GetAllParkingOutputDto($employees, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_parkings',
                'message' => $e->getMessage(),
            ]);

            return new GetAllParkingOutputDto(null, $this->notification);
        }
    }

}
