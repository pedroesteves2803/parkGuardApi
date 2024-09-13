<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

it('can create an instance of ExitVehicleOutputDtoTest with valid data', function () {
    $notification = new Notification();

    $vehicle = new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $outputDto = new ExitVehicleOutputDto($vehicle, $notification);

    expect($outputDto)->toBeInstanceOf(ExitVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBe($vehicle);
});
