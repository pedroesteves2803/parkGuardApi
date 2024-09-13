<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Application\Usecase\GetVehicleById;
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

it('can retrieve an vehicle by ID from the repository', function () {
    $notification = new Notification();

    $vehicleId = 1;

    $vehicle = new Vehicle(
        $vehicleId,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($vehicle);

    $getVehicleById = new GetVehicleById($this->repositoryMock, $notification);

    $inputDto = new GetVehicleInputDto($vehicleId);

    $outputDto = $getVehicleById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBe($vehicle);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to retrieve a non-existing vehicle', function () {
    $notification = new Notification();

    $vehicleId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $getVehicleById = new GetVehicleById($this->repositoryMock, $notification);

    $inputDto = new GetVehicleInputDto($vehicleId);

    $outputDto = $getVehicleById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'get_vehicle_by_id',
            'message' => 'Veiculo não encontrado!',
        ],
    ]);
});
