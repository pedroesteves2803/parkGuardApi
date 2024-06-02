<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

it('can create an instance of CreateVehicleOutputDto with valid data', function () {
    $notification = new Notification();

    $vehicle = new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        null
    );

    $outputDto = new CreateVehicleOutputDto($vehicle, $notification);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class);
    expect($outputDto->vehicle->manufacturer())->toBe($vehicle->manufacturer());
    expect($outputDto->vehicle->color())->toBe($vehicle->color());
    expect($outputDto->vehicle->model())->toBe($vehicle->model());
    expect($outputDto->vehicle->licensePlate())->toBe($vehicle->licensePlate());
    expect($outputDto->vehicle->entryTimes())->toEqual($vehicle->entryTimes());
    expect($outputDto->vehicle->departureTimes())->toBeNull();
});
