<?php

use Src\Administration\Application\Dtos\Employee\UpdateEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\UpdateEmployeeOutputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingInputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingOutputDto;
use Src\Administration\Application\Usecase\Employee\UpdateEmployee;
use Src\Administration\Application\Usecase\Parking\UpdateParking;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\PricePerHour;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

describe('UpdateParkingTest', function () {

    beforeEach(function () {
        $this->repositoryMock = mock(IParkingRepository::class);
        $this->factoryMock = mock(ParkingFactory::class);
    });

    it('can update an existing parking with valid input', function () {
        $notification = new Notification();

        $parkingId = 1;

        $employee = new Parking(
            $parkingId,
            new Name('Parking 24h'),
            '12345678',
            new Name('Teste'),
            new PricePerHour(10),
            new AdditionalHourPrice(5)
        );

        $employeeUpdate = new Parking(
            $parkingId,
            new Name('Parking 24h update'),
            '1112131415',
            new Name('Teste update'),
            new PricePerHour(12),
            new AdditionalHourPrice(8)
        );

        $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
//        $this->repositoryMock->shouldReceive('exists')->once()->andReturnFalse();
        $this->repositoryMock->shouldReceive('update')->once()->andReturn($employeeUpdate);

        $this->factoryMock->shouldReceive('create')->once()->andReturn($employeeUpdate);

        $updateParking = new UpdateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new UpdateParkingInputDto(
            $parkingId,
            'Parking 24h',
            '12345678',
            'Teste',
            10,
            5
        );

        $outputDto = $updateParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toEqual($employeeUpdate)
            ->and($outputDto->notification->getErrors())->toBeEmpty();
    });

    it('can update an existing parking lot with a valid entry and the same identifier', function () {
        $notification = new Notification();

        $parkingId = 1;

        $employee = new Parking(
            $parkingId,
            new Name('Parking 24h'),
            '12345678',
            new Name('Teste'),
            new PricePerHour(10),
            new AdditionalHourPrice(5)
        );

        $employeeUpdate = new Parking(
            $parkingId,
            new Name('Parking 24h update'),
            '1112131415',
            new Name('Teste update'),
            new PricePerHour(12),
            new AdditionalHourPrice(8)
        );


        $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
        $this->repositoryMock->shouldReceive('update')->once()->andReturn($employeeUpdate);
        $this->repositoryMock->shouldReceive('exists')->once()->andReturnFalse();

        $this->factoryMock->shouldReceive('create')->once()->andReturn($employeeUpdate);

        $updateParking = new UpdateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new UpdateParkingInputDto(
            $parkingId,
            'Parking 24h',
            '1112131415',
            'Teste',
            10,
            5
        );

        $outputDto = $updateParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toEqual($employeeUpdate)
            ->and($outputDto->notification->getErrors())->toBeEmpty();
    });

    it('returns error notification when trying to update an employee with non-existing ID', function () {
        $notification = new Notification();

        $parkingId = 1;

//        $this->repositoryMock->shouldReceive('exists')->once()->andReturnFalse();
        $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

        $updateParking = new UpdateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new UpdateParkingInputDto(
            $parkingId,
            'Parking 24h',
            '1112131415',
            'Teste',
            10,
            5
        );

        $outputDto = $updateParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'update_parking',
                    'message' => 'Estacionamento não cadastrado!',
                ],
            ]);
    });


    it('returns an error notification when trying to update a parking lot with an existing manager identifier', function () {
        $notification = new Notification();
        $parkingId = 1;

        $employee = new Parking(
            $parkingId,
            new Name('Parking 24h'),
            '12345678',
            new Name('Teste'),
            new PricePerHour(10),
            new AdditionalHourPrice(5)
        );

        $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
        $this->repositoryMock->shouldReceive('exists')->once()->andReturnTrue();

        $updateParking = new UpdateParking($this->repositoryMock, $notification, $this->factoryMock);

        $inputDto = new UpdateParkingInputDto(
            $parkingId,
            'Parking 24h',
            '1112131415',
            'Teste',
            10,
            5
        );

        $outputDto = $updateParking->execute($inputDto);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'update_parking',
                    'message' => 'Idenficador de responsavel já cadastrado!',
                ],
            ]);
    });

});

