<?php

namespace Src\Administration\Application\Usecase\Parking;

use Src\Administration\Application\Dtos\Parking\DeleteParkingInputDto;
use Src\Administration\Application\Dtos\Parking\DeleteParkingOutputDto;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;

final readonly class DeleteParkingById
{
    public function __construct(
        private IParkingRepository $parkingRepository,
        private Notification        $notification,
    ) {}

    public function execute(DeleteParkingInputDto $input): DeleteParkingOutputDto
    {
        try {
            $parking = $this->parkingRepository->getById(
                $input->parkingId
            );

            if ($parking) {
                $this->notification->addError([
                    'context' => 'delete_parking',
                    'message' => 'Estacionamento não encontrado!',
                ]);

                return new DeleteParkingOutputDto(null, $this->notification);
            }

            $this->parkingRepository->delete(
                $input->parkingId
            );

            return new DeleteParkingOutputDto(null, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'delete_parking',
                'message' => $e->getMessage(),
            ]);

            return new DeleteParkingOutputDto(null, $this->notification);
        }
    }

}
