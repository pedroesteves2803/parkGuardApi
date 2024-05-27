<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\AddPending;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repositoryMock = mock(IVehicleRepository::class);
});

it('successfully creates an vehicle', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existVehicle')->once()->andReturnFalse();

    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $vehicle->addConsult(
        new Consult(
            new Manufacturer('Toyota'),
            new Color('Azul'),
            new Model('Corolla'),
            new LicensePlate('ABC-1234'),
        )
    );

    $addPending = new AddPending($this->repositoryMock, $notification);

    $inputDto = new AddPendingInputDto(
        $vehicle,
        'Type 1',
        'Description 1',
    );

    $addPending->execute($inputDto);

    $this->assertDatabaseHas('pendencies', [
        'type' => 'Type1',
        'description' => 'Description1',
        'vehicle_id' => 1,
    ]);
});
