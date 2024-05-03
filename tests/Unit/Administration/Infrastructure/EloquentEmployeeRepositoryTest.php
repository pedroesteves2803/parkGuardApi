<?php

use App\Models\Employee as ModelsEmployee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Infrastructure\EloquentEmployeeRepository;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all employees', function () {
    ModelsEmployee::factory()->create([
        'name'     => 'nome 1',
        'email'    => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type'     => 1,
    ]);
    ModelsEmployee::factory()->create([
        'name'     => 'nome 2',
        'email'    => 'email2@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type'     => 2,
    ]);
    ModelsEmployee::factory()->create([
        'name'     => 'nome 3',
        'email'    => 'email3@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type'     => 2,
    ]);

    $repository = new EloquentEmployeeRepository();
    $employees = $repository->getAll();

    expect($employees)->toBeInstanceOf(Collection::class);
    expect($employees)->toHaveCount(3);
});

it('can get employee by id', function () {
    $employee = ModelsEmployee::factory()->create([
        'name'     => 'nome 1',
        'email'    => 'email1@teste.com',
        'password' => '$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe',
        'type'     => 1,
    ]);

    $repository = new EloquentEmployeeRepository();

    $retrievedEmployee = $repository->getById($employee->id);

    expect($retrievedEmployee)->toBeInstanceOf(Employee::class);

    expect($retrievedEmployee->id)->toBe($employee->id);
    expect($retrievedEmployee->name->value())->toBe($employee->name);
    expect($retrievedEmployee->email->value())->toBe($employee->email);
    expect($retrievedEmployee->password->value())->toBe($employee->password);
    expect($retrievedEmployee->type->value())->toBe($employee->type);
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
    expect($createdEmployee->id)->not->toBeNull();
    expect($createdEmployee->name->value())->toBe($employeeData->name->value());
    expect($createdEmployee->email->value())->toBe($employeeData->email->value());
    expect($createdEmployee->password->value())->toBe($employeeData->password->value());
    expect($createdEmployee->type->value())->toBe($employeeData->type->value());
});
