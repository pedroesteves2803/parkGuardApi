<?php
use App\Models\Parking as ModelsParking;
use App\Repositories\Administration\EloquentParkingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Tests\TestCase;
use Illuminate\Support\Collection;


uses(TestCase::class, RefreshDatabase::class);

describe('EloquentParkingRepository', function () {

    it('can get all parkings', function () {
        ModelsParking::factory()->create([
            'responsible_identification' => '50598542809',
            'responsible_name' => 'Estacionamento 1',
            'price_per_hour' => 12.0,
            'additional_hour_price' => 2.0,
        ]);

        ModelsParking::factory()->create([
            'responsible_identification' => '123132131',
            'responsible_name' => 'Estacionamento 2',
            'price_per_hour' => 13.0,
            'additional_hour_price' => 2.0,
        ]);

        ModelsParking::factory()->create([
            'responsible_identification' => '3123312321',
            'responsible_name' => 'Estacionamento 3',
            'price_per_hour' => 13.0,
            'additional_hour_price' => 2.0,
        ]);

        $repository = new EloquentParkingRepository(
            new ParkingFactory()
        );

        $parkings = $repository->getAll();

        expect($parkings)->toBeInstanceOf(Collection::class)
            ->and($parkings)->toHaveCount(3);
    });

it('can get parking by id', function () {

    $parking = ModelsParking::factory()->create([
        'responsible_identification' => '50598542809',
        'responsible_name' => 'Estacionamento',
        'price_per_hour' => 10.0,
        'additional_hour_price' => 2.0,
    ]);

    $repository = new EloquentParkingRepository(
        new ParkingFactory()
    );

    $retrievedParking = $repository->getById($parking->id);

    expect($retrievedParking)->toBeInstanceOf(Parking::class)
        ->and($retrievedParking->name()->value())->toBe($parking->name)
        ->and($retrievedParking->responsibleIdentification())->toBe($parking->responsible_identification)
        ->and($retrievedParking->responsibleName()->value())->toBe($parking->responsible_name)
        ->and($retrievedParking->pricePerHour()->value())->toBe($parking->price_per_hour)
        ->and($retrievedParking->additionalHourPrice()->value())->toBe($parking->additional_hour_price);
});

    it('creates a new parking', function () {

        $parkingFactory = new ParkingFactory();

        $parkingData = $parkingFactory->create(
            null,
            'Parking',
            '12345678945',
            'Teste name',
            10,
            5
        );

        $repository = new EloquentParkingRepository(
            $parkingFactory
        );

        $createdParking = $repository->create($parkingData);

        expect($createdParking)->toBeInstanceOf(Parking::class);

        $this->assertNotNull($createdParking->id());

        expect($createdParking->name()->value())->toBe($parkingData->name()->value())
            ->and($createdParking->responsibleIdentification())->toBe($parkingData->responsibleIdentification())
            ->and($createdParking->responsibleName()->value())->toBe($parkingData->responsibleName()->value())
            ->and($createdParking->pricePerHour()->value())->toBe($parkingData->pricePerHour()->value())
            ->and($createdParking->additionalHourPrice()->value())->toBe($parkingData->additionalHourPrice()->value());
    });

it('update a parking', function () {
    ModelsParking::factory()->create([
        'name' => 'teste',
        'responsible_identification' => '50598542809',
        'responsible_name' => 'Estacionamento',
        'price_per_hour' => 10.0,
        'additional_hour_price' => 2.0,
    ]);

    $parkingFactory = new ParkingFactory();

    $parkingData = $parkingFactory->create(
        1,
        'Parking',
        '12345678945',
        'Teste name',
        10,
        5
    );

    $repository = new EloquentParkingRepository(
        $parkingFactory
    );

    $updateParking = $repository->update($parkingData);

    expect($updateParking)->toBeInstanceOf(Parking::class);
    $this->assertNotNull($updateParking->id());
    expect($updateParking->name()->value())->toBe($parkingData->name()->value())
        ->and($updateParking->responsibleIdentification())->toBe($parkingData->responsibleIdentification())
        ->and($updateParking->responsibleName()->value())->toBe($parkingData->responsibleName()->value())
        ->and($updateParking->pricePerHour()->value())->toBe($parkingData->pricePerHour()->value())
        ->and($updateParking->additionalHourPrice()->value())->toBe($parkingData->additionalHourPrice()->value());
});

//it('delete a employee', function () {
//    ModelsEmployee::factory()->create([
//        'name' => 'nome 1',
//        'email' => 'email1@teste.com',
//        'type' => 1,
//    ]);
//
//    $repository = new EloquentEmployeeRepository(
//        new EmployeeFactory()
//    );
//    $repository->delete(1);
//    $deleteEmployee = $repository->getById(1);
//
//    $this->assertNull($deleteEmployee);
//});

    it('check if there is an parking', function () {
        ModelsParking::factory()->create([
            'responsible_identification' => '50598542809',
            'responsible_name' => 'Estacionamento',
            'price_per_hour' => 10.0,
            'additional_hour_price' => 2.0,
        ]);

        $repository = new EloquentParkingRepository(
            new ParkingFactory()
        );
        $existParking = $repository->exists(
            '50598542809'
        );

        expect($existParking)->toBeTrue();
    });

    it('check if there is no parking', function () {
        ModelsParking::factory()->create([
            'responsible_identification' => '50598542809',
            'responsible_name' => 'Estacionamento',
            'price_per_hour' => 10.0,
            'additional_hour_price' => 2.0,
        ]);

        $repository = new EloquentParkingRepository(
            new ParkingFactory()
        );
        $existParking = $repository->exists(
            '50598542825525'
        );

        expect($existParking)->toBeFalse();
    });

});
