<?php

use App\Models\Parking as ModelsParking;
use App\Repositories\Administration\EloquentParkingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('EloquentParkingRepository', function () {
    //it('can get all employees', function () {
//    ModelsEmployee::factory()->create([
//        'name' => 'nome 1',
//        'email' => 'email1@teste.com',
//        'type' => 1,
//    ]);
//    ModelsEmployee::factory()->create([
//        'name' => 'nome 2',
//        'email' => 'email2@teste.com',
//        'type' => 2,
//    ]);
//    ModelsEmployee::factory()->create([
//        'name' => 'nome 3',
//        'email' => 'email3@teste.com',
//        'type' => 2,
//    ]);
//
//    $repository = new EloquentEmployeeRepository(
//        new EmployeeFactory()
//    );
//    $employees = $repository->getAll();
//
//    expect($employees)->toBeInstanceOf(Collection::class)
//        ->and($employees)->toHaveCount(3);
//});

//it('can get employee by id', function () {
//    $employee = ModelsEmployee::factory()->create([
//        'name' => 'nome 1',
//        'email' => 'email1@teste.com',
//        'type' => 1,
//    ]);
//
//    $repository = new EloquentEmployeeRepository(
//        new EmployeeFactory()
//    );
//
//    $retrievedEmployee = $repository->getById($employee->id);
//
//    expect($retrievedEmployee)->toBeInstanceOf(Employee::class)
//        ->and($retrievedEmployee->id())->toBe($employee->id)
//        ->and($retrievedEmployee->name()->value())->toBe($employee->name)
//        ->and($retrievedEmployee->email()->value())->toBe($employee->email)
//        ->and($retrievedEmployee->password()->value())->toBe($employee->password)
//        ->and($retrievedEmployee->type()->value())->toBe($employee->type);
//
//});

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

//it('update a employee', function () {
//    ModelsEmployee::factory()->create([
//        'name' => 'nome 1',
//        'email' => 'email1@teste.com',
//        'type' => 1,
//    ]);
//
//    $employeeData = new Employee(
//        1,
//        new Name('Update'),
//        new Email('update@test.com'),
//        new Password('Password@123'),
//        new Type(1),
//        null
//    );
//
//    $repository = new EloquentEmployeeRepository(
//        new EmployeeFactory()
//    );
//    $createdEmployee = $repository->update($employeeData);
//
//    expect($createdEmployee)->toBeInstanceOf(Employee::class);
//    $this->assertNotNull($createdEmployee->id());
//    expect($createdEmployee->name()->value())->toBe($employeeData->name()->value())
//        ->and($createdEmployee->email()->value())->toBe($employeeData->email()->value())
//        ->and($createdEmployee->type()->value())->toBe($employeeData->type()->value());
//});

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
