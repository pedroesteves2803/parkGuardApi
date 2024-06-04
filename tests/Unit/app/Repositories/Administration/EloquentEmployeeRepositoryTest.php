<?php

use App\Models\Employee as ModelsEmployee;
use App\Repositories\Administration\EloquentEmployeeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all employees', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);
    ModelsEmployee::factory()->create([
        'name' => 'nome 2',
        'email' => 'email2@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 2,
    ]);
    ModelsEmployee::factory()->create([
        'name' => 'nome 3',
        'email' => 'email3@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 2,
    ]);

    $repository = new EloquentEmployeeRepository();
    $employees = $repository->getAll();

    expect($employees)->toBeInstanceOf(Collection::class);
    expect($employees)->toHaveCount(3);
});

it('can get employee by id', function () {
    $employee = ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository();

    $retrievedEmployee = $repository->getById($employee->id);

    expect($retrievedEmployee)->toBeInstanceOf(Employee::class);

    expect($retrievedEmployee->id())->toBe($employee->id);
    expect($retrievedEmployee->name()->value())->toBe($employee->name);
    expect($retrievedEmployee->email()->value())->toBe($employee->email);
    expect($retrievedEmployee->password()->value())->toBe($employee->password);
    expect($retrievedEmployee->type()->value())->toBe($employee->type);
});

it('creates a new employee', function () {
    $employeeData = new Employee(
        null,
        new Name('Nome'),
        new Email('email@test.com'),
        new Password('Password@123'),
        new Type(1)
    );

    $repository = new EloquentEmployeeRepository();
    $createdEmployee = $repository->create($employeeData);

    expect($createdEmployee)->toBeInstanceOf(Employee::class);
    $this->assertNotNull($createdEmployee->id());
    expect($createdEmployee->name()->value())->toBe($employeeData->name()->value());
    expect($createdEmployee->email()->value())->toBe($employeeData->email()->value());
    expect($createdEmployee->type()->value())->toBe($employeeData->type()->value());
});

it('update a employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);

    $employeeData = new Employee(
        1,
        new Name('Update'),
        new Email('update@test.com'),
        new Password('Password@123'),
        new Type(1)
    );

    $repository = new EloquentEmployeeRepository();
    $createdEmployee = $repository->update($employeeData);

    expect($createdEmployee)->toBeInstanceOf(Employee::class);
    $this->assertNotNull($createdEmployee->id());
    expect($createdEmployee->name()->value())->toBe($employeeData->name()->value());
    expect($createdEmployee->email()->value())->toBe($employeeData->email()->value());
    expect($createdEmployee->type()->value())->toBe($employeeData->type()->value());
});

it('delete a employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository();
    $repository->delete(1);
    $deleteEmployee = $repository->getById(1);

    $this->assertNull($deleteEmployee);
});

it('check if there is an employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository();
    $existEmployee = $repository->existByEmail(
        new Email('email1@teste.com')
    );

    expect($existEmployee)->toBeTrue();
});

it('check if there is no employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository();
    $existEmployee = $repository->existByEmail(
        new Email('emailerrado@teste.com')
    );

    expect($existEmployee)->toBeFalse();
});
