<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;

it('can create an instance of ConsultVehicleByLicensePlateOutputDto with valid data', function () {
    $notification = new Notification();

    $outputDto = new ConsultVehicleByLicensePlateOutputDto(
        "Toyota",
        "Azul",
        "Corolla",
        "ABC1234",
        [
            new Pending(
                null,
                new Type('Tipo 1'),
                new Description('SEM RESTRICAO'),
            ),
            new Pending(
                null,
                new Type('Tipo 2'),
                new Description('SEM RESTRICAO'),
            ),
        ],
        $notification
    );

    expect($outputDto)->toBeInstanceOf(ConsultVehicleByLicensePlateOutputDto::class);
    expect($outputDto->manufacturer)->toBe("Toyota");
    expect($outputDto->color)->toBe("Azul");
    expect($outputDto->model)->toBe("Corolla");
    expect($outputDto->licensePlate)->toBe("ABC1234");

    $expectedPendings = [
        new Pending(null, new Type('Tipo 1'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo 2'), new Description('SEM RESTRICAO')),
    ];

    foreach ($outputDto->pending as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }
});
