<?php

use Src\Administration\Application\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Dtos\CreateParkingInputDto;
use Src\Administration\Application\Dtos\CreateParkingOutputDto;
use Src\Administration\Application\Usecase\CreateEmployee;
use Src\Administration\Application\Usecase\CreateParking;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Shared\Utils\Notification;


describe('CreateParkingTest', function () {
    beforeEach(function () {
        $this->repositoryMock = mock(IParkingRepository::class);
        $this->factoryMock = mock(ParkingFactory::class);
    });

    it('successfully creates an parking', function () {
        $notification = new Notification();

        $parking = (new ParkingFactory())->create(
            '1',
            'Nome estacionamento',
            '12345678',
            'Nome responsalvel',
            10,
             2
        );

        $this->repositoryMock->shouldReceive('exists')->once()->andReturnFalse();
        $this->repositoryMock->shouldReceive('create')->once()->andReturn($parking);
        $this->factoryMock->shouldReceive('create')->once()->andReturn($parking);

        $createParking = new CreateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new CreateParkingInputDto(
            'Nome',
            'email@test.com',
            'Password@123',
            50,
            10
        );

        $outputDto = $createParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(CreateParkingOutputDto::class)
            ->and($outputDto->parking)->toBeInstanceOf(Parking::class)
            ->and($outputDto->parking->id())->toBe($parking->id())
            ->and($outputDto->parking->name()->value())->toBe($parking->name()->value())
            ->and($outputDto->parking->responsibleIdentification())->toBe($parking->responsibleIdentification())
            ->and($outputDto->parking->responsibleName()->value())->toBe($parking->responsibleName()->value())
            ->and($outputDto->parking->pricePerHour()->value())->toBe($parking->pricePerHour()->value())
            ->and($outputDto->parking->additionalHourPrice()->value())->toBe($parking->additionalHourPrice()->value())
            ->and($outputDto->notification->getErrors())->toBeEmpty();
    });

    it('fails to create an parking with existing', function () {
        $notification = new Notification();

        $this->repositoryMock->shouldReceive('exists')->once()->andReturnTrue();

        $createParking = new CreateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new CreateParkingInputDto(
            'Nome',
            'email@test.com',
            'Password@123',
            50,
            10
        );

        $outputDto = $createParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(CreateParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'create_parking',
                    'message' => 'Estacionamento já cadastrado!',
                ],
            ]);
    });
});

