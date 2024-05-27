<?php

use Illuminate\Database\Eloquent\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetAllVehiclesOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

it('can create an instance of GetAllVehiclesOutputDto with employees collection and notification', function () {
    $vehicles = new Collection();

    for ($i = 0; $i < 10; ++$i) {
        $id = $i + 1;
        $manufacturer = 'Marca vehicle '.($i + 1);
        $color = 'Vehicle '.($i + 1);
        $model = 'Model '.($i + 1);
        $licensePlate = 'ABC-123'.$i;

        $vehicles->push(
            new Vehicle(
                $id,
                new Manufacturer($manufacturer),
                new Color($color),
                new Model($model),
                new LicensePlate($licensePlate),
                new EntryTimes(
                    new DateTime()
                ),
                new DepartureTimes(
                    new DateTime()
                )
            )
        );
    }

    $notification = new Notification();

    $outputDto = new GetAllVehiclesOutputDto($vehicles, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllVehiclesOutputDto::class);
    expect($outputDto->vehicles)->toBe($vehicles);
    $this->assertCount($vehicles->count(), $outputDto->vehicles);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GetAllVehiclesOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetAllVehiclesOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllVehiclesOutputDto::class);
    expect($outputDto->vehicles)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
