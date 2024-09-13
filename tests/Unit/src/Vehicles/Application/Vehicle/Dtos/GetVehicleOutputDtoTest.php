<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

it('can create an instance of GetVehicleOutputDto with a valid vehicle', function () {
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

    $outputDto = new GetVehicleOutputDto($vehicle, $notification);

    expect($outputDto)->toBeInstanceOf(GetVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBe($vehicle);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GetVehicleOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetVehicleOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
