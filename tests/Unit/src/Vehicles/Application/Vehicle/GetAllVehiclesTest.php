<?php

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\GetAllVehiclesOutputDto;
use Src\Vehicles\Application\Usecase\GetAllVehicles;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

beforeEach(function () {
    $this->repositoryMock = mock(IVehicleRepository::class);
});

it('can retrieve all vehicles', function () {
    $notification = new Notification();

    $vehicles = new Collection();

    for ($i = 0; $i < 10; $i++) {
        $id = $i + 1;
        $manufacturer = 'Manufacturer '.($i + 1);
        $color = 'Color'.($i + 1);
        $model = 'Model'.($i + 1);
        $licensePlate = 'ABC-1234';

        $vehicles->push(
            new Vehicle(
                $id,
                new Manufacturer($manufacturer),
                new Color($color),
                new Model($model),
                new LicensePlate($licensePlate),
                new EntryTimes(new DateTime()),
                null
            )
        );
    }

    $this->repositoryMock->shouldReceive('getAll')->once()->andReturn($vehicles);

    $getAllVehicles = new GetAllVehicles($this->repositoryMock, $notification);

    $outputDto = $getAllVehicles->execute();

    expect($outputDto)->toBeInstanceOf(GetAllVehiclesOutputDto::class)
        ->and($outputDto->vehicles)->toBe($vehicles)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when there are no vehicles', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('getAll')->once()->andReturn(new Collection());

    $getAllVehicles = new GetAllVehicles($this->repositoryMock, $notification);

    $outputDto = $getAllVehicles->execute();

    expect($outputDto)->toBeInstanceOf(GetAllVehiclesOutputDto::class)
        ->and($outputDto->vehicles)->toBeInstanceOf(Collection::class)
        ->and($outputDto->vehicles->isEmpty())->toBeTrue()
        ->and($outputDto->notification->getErrors())->toMatchArray([
            [
                'context' => 'get_all_vehicle',
                'message' => 'Não possui veículos!',
            ],
        ]);
});

