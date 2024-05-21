<?php

use App\Models\Vehicle as ModelsVehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Infrastructure\EloquentVehicleRepository;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all vehicles', function () {
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Honda',
        'color'           => 'Azul',
        'model'           => 'Civic',
        'license_plate'   => 'DGF-1798',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $vehicles = $repository->getAll();

    expect($vehicles)->toBeInstanceOf(Collection::class);
    expect($vehicles)->toHaveCount(2);
});

it('can get vehicle by id', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();

    $retrievedVehicle = $repository->getById($vehicle->id);

    expect($retrievedVehicle)->toBeInstanceOf(Vehicle::class);

    expect($retrievedVehicle->id)->toBe($vehicle->id);
    expect($retrievedVehicle->manufacturer->value())->toBe($vehicle->manufacturer);
    expect($retrievedVehicle->color->value())->toBe($vehicle->color);
    expect($retrievedVehicle->model->value())->toBe($vehicle->model);
    expect($retrievedVehicle->licensePlate->value())->toBe($vehicle->license_plate);
    expect($retrievedVehicle->departureTimes->value())->toBe($vehicle->departure_times);
});

it('creates a new vehicle', function () {
    $vehicleData = new Vehicle(
        null,
        new Manufacturer('Honda'),
        new Color('Azul'),
        new Model('Civic'),
        new LicensePlate('DEF-1028'),
        new EntryTimes(new DateTime()),
        null
    );

    $repository = new EloquentVehicleRepository();
    $createdVehicle = $repository->create($vehicleData);

    expect($createdVehicle)->toBeInstanceOf(Vehicle::class);
    $this->assertNotNull($createdVehicle->id);
    expect($createdVehicle->manufacturer->value())->toBe($vehicleData->manufacturer->value());
    expect($createdVehicle->color->value())->toBe($vehicleData->color->value());
    expect($createdVehicle->model->value())->toBe($vehicleData->model->value());
    expect($createdVehicle->licensePlate->value())->toBe($vehicleData->licensePlate->value());
    $this->assertNull($createdVehicle->departureTimes);
});

it('update a vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleData = new Vehicle(
        1,
        new Manufacturer('Honda'),
        new Color('Azul'),
        new Model('Civic'),
        new LicensePlate('DEF-1028'),
        new EntryTimes(new DateTime()),
        null
    );

    $repository = new EloquentVehicleRepository();
    $createdVehicle = $repository->update($vehicleData);

    expect($createdVehicle)->toBeInstanceOf(Vehicle::class);
    $this->assertNotNull($createdVehicle->id);
    expect($createdVehicle->manufacturer->value())->toBe($vehicleData->manufacturer->value());
    expect($createdVehicle->color->value())->toBe($vehicleData->color->value());
    expect($createdVehicle->model->value())->toBe($vehicleData->model->value());
    expect($createdVehicle->licensePlate->value())->toBe($vehicleData->licensePlate->value());
    $this->assertNull($createdVehicle->departureTimes);
});

it('check if there is an vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $existVehicle = $repository->existVehicle(
        new LicensePlate('DEF-1028')
    );

    expect($existVehicle)->toBeTrue();
});

it('check if there is no vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $existVehicle = $repository->existVehicle(
        new LicensePlate('DEF-1000')
    );

    expect($existVehicle)->toBeFalse();
});

it('add exit to vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer'    => 'Fiat',
        'color'           => 'Preto',
        'model'           => 'Uno',
        'license_plate'   => 'DEF-1028',
        'entry_times'     => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $vehicle = $repository->exit(
        new LicensePlate('DEF-1028')
    );

    $this->assertNotNull($vehicle->departureTimes);
});

