<?php

use Src\Administration\Application\Dtos\Parking\GetParkingByIdInputDto;
use Src\Administration\Application\Dtos\Parking\GetParkingByIdOutputDto;
use Src\Administration\Application\Usecase\Parking\GetParkingById;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;


describe('Test GetParkingById', function () {

    beforeEach(function () {
        $this->repositoryMock = mock(IParkingRepository::class);
    });

    it('can retrieve parking', function () {

            $id = 1;
            $name = 'Parking 24h';
            $responsibleIdentification = '123456789';
            $responsibleName = 'Test Responsible';
            $pricePerHour = 20.0;
            $additionalHourPrice = 10.0;

            $parkingFactory = new ParkingFactory();

            $parking = $parkingFactory->create(
                $id,
                $name,
                $responsibleIdentification,
                $responsibleName,
                $pricePerHour,
                $additionalHourPrice,
            );

        $notification = new Notification();

        $this->repositoryMock->shouldReceive('getById')->once()->andReturn($parking);

        $getParkingById = new GetParkingById($this->repositoryMock, $notification);

        $inputDto = new GetParkingByIdInputDto(
            1,
        );

        $outputDto = $getParkingById->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(GetParkingByIdOutputDto::class)
            ->and($outputDto->parking)->toEqual($parking)
            ->and($outputDto->notification->getErrors())->toBeEmpty();
    });

    it('returns error notification when there are no parking', function () {
        $notification = new Notification();

        $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

        $getParkingById = new GetParkingById($this->repositoryMock, $notification);

        $inputDto = new GetParkingByIdInputDto(
            1,
        );

        $outputDto = $getParkingById->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(GetParkingByIdOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'get_parking_by_id',
                    'message' => 'Estacionamento não encontrado!',
                ],
            ]);

    });

});
