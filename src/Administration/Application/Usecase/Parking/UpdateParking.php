<?php

namespace Src\Administration\Application\Usecase\Parking;

use Src\Administration\Application\Dtos\Parking\UpdateParkingInputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingOutputDto;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;

final readonly class UpdateParking
{
    public function __construct(
        private IParkingRepository $parkingRepository,
        private Notification        $notification,
        private ParkingFactory     $parkingFactory
    ) {}

    public function execute(UpdateParkingInputDto $input): UpdateParkingOutputDto
    {
        try {
            $parking = $this->parkingRepository->getById(
                $input->id
            );

            if (is_null($parking)){
                $this->notification->addError([
                    'context' => 'update_parking',
                    'message' => 'Estacionamento não cadastrado!',
                ]);

                return new UpdateParkingOutputDto(null, $this->notification);
            }

            $employee = $this->parkingRepository->update(
                $this->parkingFactory->create(
                    $input->id,
                    $input->name,
                    $input->responsibleIdentification,
                    $input->responsibleName,
                    $input->pricePerHour,
                    $input->additionalHourPrice,
                )
            );

            return new UpdateParkingOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'update_parking',
                'message' => $e->getMessage(),
            ]);

            return new UpdateParkingOutputDto(null, $this->notification);
        }
    }
}
