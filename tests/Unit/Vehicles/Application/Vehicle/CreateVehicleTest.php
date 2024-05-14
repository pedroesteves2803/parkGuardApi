<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\CreateVehicle;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

beforeEach(function () {
    $this->repositoryMock = mock(IVehicleRepository::class);
});

it('successfully creates an vehicle', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existVehicle')->once()->andReturnFalse();
    $this->repositoryMock->shouldReceive('create')->once()->andReturn(
        new Vehicle(
            null,
            new Manufacturer('Toyota'),
            new Color('Azul'),
            new Model('Corolla'),
            new LicensePlate('ABC-1234'),
            new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
        )
    );

    $createVehicle = new CreateVehicle($this->repositoryMock, $notification);

    $inputDto = new CreateVehicleInputDto(
        'Toyota',
        'Azul',
        'Corolla',
        'ABC-1234',
        new DateTime('2024-05-12 08:00:00'),
        new DateTime('2024-05-12 17:00:00')
    );

    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeInstanceOf(Vehicle::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to create an employee with existing email', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existVehicle')->once()->andReturnTrue();
    $createVehicle = new CreateVehicle($this->repositoryMock, $notification);

    $inputDto = new CreateVehicleInputDto(
        'Toyota',
        'Azul',
        'Corolla',
        'ABC-1234',
        new DateTime('2024-05-12 08:00:00'),
        new DateTime('2024-05-12 17:00:00')
    );
    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'license_plate_already_exists',
            'message' => 'Placa já cadastrado!',
        ],
    ]);
});
