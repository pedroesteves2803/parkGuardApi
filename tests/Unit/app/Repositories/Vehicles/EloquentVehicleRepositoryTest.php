<?php

use App\Models\Vehicle as ModelsVehicle;
use App\Repositories\Vehicles\EloquentVehicleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all vehicles', function () {
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $vehicles = $repository->getAll();

    expect($vehicles)->toBeInstanceOf(Collection::class)
        ->and($vehicles)->toHaveCount(2);
});

it('can get vehicle by id', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();

    $retrievedVehicle = $repository->getById($vehicle->id);

    expect($retrievedVehicle)->toBeInstanceOf(Vehicle::class)
        ->and($retrievedVehicle->id())->toBe($vehicle->id)
        ->and($retrievedVehicle->manufacturer()->value())->toBe($vehicle->manufacturer)
        ->and($retrievedVehicle->color()->value())->toBe($vehicle->color)
        ->and($retrievedVehicle->model()->value())->toBe($vehicle->model)
        ->and($retrievedVehicle->licensePlate()->value())->toBe($vehicle->license_plate)
        ->and($retrievedVehicle->departureTimes())->toBe($vehicle->departure_times);

});

it('creates a new vehicle', function () {
    $vehicleData = new Vehicle(
        null,
        new Manufacturer('Honda'),
        new Color('Azul'),
        new Model('Civic'),
        new LicensePlate('DEF1028'),
        new EntryTimes(new DateTime()),
        null
    );

    $pending = new Pending(
                null,
                new Type('Type 1'),
                new Description('Description 1')
    );

    $vehicleData->addPending($pending);

    $repository = new EloquentVehicleRepository();
    $createdVehicle = $repository->create($vehicleData);

    expect($createdVehicle)->toBeInstanceOf(Vehicle::class);
    $this->assertNotNull($createdVehicle->id());
    expect($createdVehicle->manufacturer()->value())->toBe($vehicleData->manufacturer()->value())
        ->and($createdVehicle->color()->value())->toBe($vehicleData->color()->value())
        ->and($createdVehicle->model()->value())->toBe($vehicleData->model()->value())
        ->and($createdVehicle->licensePlate()->value())->toBe($vehicleData->licensePlate()->value());
    $this->assertNull($createdVehicle->departureTimes()->value());

    expect($createdVehicle->pending())->toBeInstanceOf(Collection::class)
        ->and($createdVehicle->pending())->toHaveCount(1)
        ->and($createdVehicle->pending()[0]->type()->value())->toBe('Type 1')
        ->and($createdVehicle->pending()[0]->description()->value())->toBe('Description 1');
});

it('update a vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleData = new Vehicle(
        1,
        new Manufacturer('Honda'),
        new Color('Azul'),
        new Model('Civic'),
        new LicensePlate('DEF1028'),
        new EntryTimes(new DateTime()),
        null
    );

    $repository = new EloquentVehicleRepository();
    $createdVehicle = $repository->update($vehicleData);

    expect($createdVehicle)->toBeInstanceOf(Vehicle::class);
    $this->assertNotNull($createdVehicle->id());
    expect($createdVehicle->manufacturer()->value())->toBe($vehicleData->manufacturer()->value())
        ->and($createdVehicle->color()->value())->toBe($vehicleData->color()->value())
        ->and($createdVehicle->model()->value())->toBe($vehicleData->model()->value())
        ->and($createdVehicle->licensePlate()->value())->toBe($vehicleData->licensePlate()->value());
    $this->assertNull($createdVehicle->departureTimes());
});

it('check if there is an vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $existVehicle = $repository->existVehicle(
        new LicensePlate('DEF1028')
    );

    expect($existVehicle)->toBeTrue();
});

it('check if there is no vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $existVehicle = $repository->existVehicle(
        new LicensePlate('DEF1000')
    );

    expect($existVehicle)->toBeFalse();
});

it('add exit to vehicle', function () {
    ModelsVehicle::factory()->create([
        'manufacturer' => 'Fiat',
        'color' => 'Preto',
        'model' => 'Uno',
        'license_plate' => 'DEF1028',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $repository = new EloquentVehicleRepository();
    $vehicle = $repository->exit(
        new LicensePlate('DEF1028')
    );

    $this->assertNotNull($vehicle->departureTimes());
});
