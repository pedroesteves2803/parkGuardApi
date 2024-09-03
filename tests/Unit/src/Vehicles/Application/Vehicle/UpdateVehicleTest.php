<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleOutputDto;
use Src\Vehicles\Application\Vehicle\UpdateVehicle;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Factory\VehicleFactory;
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

it('can update an existing vehicle with valid input', function () {
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

    $vehicleUpdate = new Vehicle(
        $vehicleId,
        new Manufacturer('Toyota'),
        new Color('Verde'),
        new Model('Corolla'),
        new LicensePlate('ABC-4567'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $this->repositoryMock->shouldReceive('update')->once()->andReturn($vehicleUpdate);

    $updateVehicle = new UpdateVehicle($this->repositoryMock, $notification, new VehicleFactory());

    $inputDto = new UpdateVehicleInputDto(
        $vehicleId,
        'Toyota',
        'Verde',
        'Corolla',
        'ABC-4567',
    );

    $outputDto = $updateVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBe($vehicleUpdate)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to update an vehicle with non-existing ID', function () {
    $notification = new Notification();

    $vehicleId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $updateVehicle = new UpdateVehicle($this->repositoryMock, $notification, new VehicleFactory());

    $inputDto = new UpdateVehicleInputDto(
        $vehicleId,
        'Toyota',
        'Verde',
        'Corolla',
        'ABC-4567',
    );

    $outputDto = $updateVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'update_vehicle',
                'message' => 'Veiculo não encontrado!',
            ],
        ]);
});
