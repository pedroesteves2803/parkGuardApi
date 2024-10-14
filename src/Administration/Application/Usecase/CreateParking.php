<?php

namespace Src\Administration\Application\Usecase;

use Src\Administration\Application\Dtos\CreateParkingInputDto;
use Src\Administration\Application\Dtos\CreateParkingOutputDto;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;

final readonly class CreateParking
{
    public function __construct(
        private IParkingRepository $parkingRepository,
        private Notification        $notification,
        private ParkingFactory     $parkingFactory
    ) {}

    public function execute(CreateParkingInputDto $input): CreateParkingOutputDto
    {
        try {
            $existParking = $this->parkingRepository->exists(
                $input->responsibleIdentification
            );

            if ($existParking) {
                $this->notification->addError([
                    'context' => 'create_parking',
                    'message' => 'Estacionamento já cadastrado!',
                ]);

                return new CreateParkingOutputDto(null, $this->notification);
            }

            $employee = $this->parkingRepository->create(
                $this->parkingFactory->create(
                    null,
                    $input->name,
                    $input->responsibleIdentification,
                    $input->responsibleName,
                    $input->pricePerHour,
                    $input->additionalHourPrice,
                )
            );

            return new CreateParkingOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'create_parking',
                'message' => $e->getMessage(),
            ]);

            return new CreateParkingOutputDto(null, $this->notification);
        }
    }
}
