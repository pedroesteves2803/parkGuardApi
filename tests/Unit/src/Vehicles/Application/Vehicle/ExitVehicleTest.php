<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExistVehicleOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Application\Vehicle\ExistVehicleById;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
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
    $this->existVehicleByIdMock = mock(ExistVehicleById::class);
});

it('can exit an existing vehicle with a valid entry', function () {
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

    $this->repositoryMock->shouldReceive('exit')->once()->andReturn($vehicle);

    $this->existVehicleByIdMock->shouldReceive('execute')->once()->andReturn(
        new ExistVehicleOutputDto(
            true,
            new Notification()
        )
    );

    $exitVehicle = new ExitVehicle(
        $this->repositoryMock,
        $notification,
        $this->existVehicleByIdMock
    );

    $inputDto = new ExitVehicleInputDto('ABC-1234');

    $outputDto = $exitVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(ExitVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBe($vehicle)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns an error notification when attempting to exit a vehicle with a non-existent entry', function () {
    $notification = new Notification();

    $this->existVehicleByIdMock->shouldReceive('execute')->once()->andReturn(
        new ExistVehicleOutputDto(
            false,
            new Notification()
        )
    );

    $exitVehicle = new ExitVehicle(
        $this->repositoryMock,
        $notification,
        $this->existVehicleByIdMock
    );

    $inputDto = new ExitVehicleInputDto('ABC-1234');

    $outputDto = $exitVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(ExitVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'exit_vehicle',
                'message' => 'Veículo não encontrado!',
            ],
        ]);
});
