<?php

use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

test('validates instance vehicle', function () {
    $vehicle = createValidVehicle();
    expect($vehicle)->toBeInstanceOf(Vehicle::class);
});

it('validates a valid vehicle', function () {
    $vehicle = createValidVehicle();

    expect($vehicle->id())->toBe(1);
    expect($vehicle->manufacturer()->value())->toBe('Toyota');
    expect($vehicle->color->value())->toBe('Azul');
    expect($vehicle->model->value())->toBe('Corolla');
    expect($vehicle->licensePlate()->value())->toBe('ABC-1234');
    expect($vehicle->entryTimes()->value())->toEqual(new DateTime('2024-05-12 08:00:00'));
    expect($vehicle->departureTimes()->value())->toEqual(new DateTime('2024-05-12 17:00:00'));
});

function createValidVehicle()
{
    return new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );
}
