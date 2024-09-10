<?php

use DateTime as GlobalDateTime;
use Src\Payments\Application\Payment\CalculateValue;
use Src\Payments\Application\Payment\Dtos\CalculateValueOutputDto;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

it('successfully calculate value', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-05-12 09:00:00')),
    );


    $calculateValue = new CalculateValue(
        new Notification()
    );

    $outputDto = $calculateValue->execute($vehicle);

    expect($outputDto)->toBeInstanceOf(CalculateValueOutputDto::class)
        ->and($outputDto->totalToPay)->toBe('2000')
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails calculate value', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        null,
    );


    $calculateValue = new CalculateValue(
        new Notification()
    );

    $outputDto = $calculateValue->execute($vehicle);

    expect($outputDto)->toBeInstanceOf(CalculateValueOutputDto::class)
        ->and($outputDto->totalToPay)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'calculate_value',
                'message' => 'Horário de partida não está definido!',
            ],
        ]);
});
