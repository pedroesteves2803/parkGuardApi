<?php

namespace Src\Administration\Application\Usecase\Parking;

use Src\Administration\Application\Dtos\Parking\GetParkingByIdInputDto;
use Src\Administration\Application\Dtos\Parking\GetParkingByIdOutputDto;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;

final readonly class GetParkingById
{
    public function __construct(
        private IParkingRepository $parkingRepository,
        private Notification        $notification,
    ) {}

    public function execute(
        GetParkingByIdInputDto $getParkingByIdInputDto
    ): GetParkingByIdOutputDto
    {
        try {
            $parking = $this->parkingRepository->getById($getParkingByIdInputDto->parkingId);

            if (is_null($parking)) {
                $this->notification->addError([
                    'context' => 'get_parking_by_id',
                    'message' => 'Estacionamento não encontrado!',
                ]);

                return new GetParkingByIdOutputDto(null, $this->notification);
            }

            return new GetParkingByIdOutputDto($parking, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_parking_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetParkingByIdOutputDto(null, $this->notification);
        }
    }
}
