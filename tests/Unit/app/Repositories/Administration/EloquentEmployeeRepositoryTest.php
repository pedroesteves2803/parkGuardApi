<?php

use App\Models\Employee as ModelsEmployee;
use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use App\Repositories\Administration\EloquentEmployeeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Factory\EmployeeFactory;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all employees', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);
    ModelsEmployee::factory()->create([
        'name' => 'nome 2',
        'email' => 'email2@teste.com',
        'type' => 2,
    ]);
    ModelsEmployee::factory()->create([
        'name' => 'nome 3',
        'email' => 'email3@teste.com',
        'type' => 2,
    ]);

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $employees = $repository->getAll();

    expect($employees)->toBeInstanceOf(Collection::class)
        ->and($employees)->toHaveCount(3);
});

it('can get employee by id', function () {
    $employee = ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );

    $retrievedEmployee = $repository->getById($employee->id);

    expect($retrievedEmployee)->toBeInstanceOf(Employee::class)
        ->and($retrievedEmployee->id())->toBe($employee->id)
        ->and($retrievedEmployee->name()->value())->toBe($employee->name)
        ->and($retrievedEmployee->email()->value())->toBe($employee->email)
        ->and($retrievedEmployee->password()->value())->toBe($employee->password)
        ->and($retrievedEmployee->type()->value())->toBe($employee->type);

});

it('creates a new employee', function () {
    $employeeData = new Employee(
        null,
        new Name('Nome'),
        new Email('email@test.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $createdEmployee = $repository->create($employeeData);

    expect($createdEmployee)->toBeInstanceOf(Employee::class);
    $this->assertNotNull($createdEmployee->id());
    expect($createdEmployee->name()->value())->toBe($employeeData->name()->value())
        ->and($createdEmployee->email()->value())->toBe($employeeData->email()->value())
        ->and($createdEmployee->type()->value())->toBe($employeeData->type()->value());
});

it('update a employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $employeeData = new Employee(
        1,
        new Name('Update'),
        new Email('update@test.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $createdEmployee = $repository->update($employeeData);

    expect($createdEmployee)->toBeInstanceOf(Employee::class);
    $this->assertNotNull($createdEmployee->id());
    expect($createdEmployee->name()->value())->toBe($employeeData->name()->value())
        ->and($createdEmployee->email()->value())->toBe($employeeData->email()->value())
        ->and($createdEmployee->type()->value())->toBe($employeeData->type()->value());
});

it('delete a employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $repository->delete(1);
    $deleteEmployee = $repository->getById(1);

    $this->assertNull($deleteEmployee);
});

it('check if there is an employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $existEmployee = $repository->existByEmail(
        new Email('email1@teste.com')
    );

    expect($existEmployee)->toBeTrue();
});

it('check if there is no employee', function () {
    ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $existEmployee = $repository->existByEmail(
        new Email('emailerrado@teste.com')
    );

    expect($existEmployee)->toBeFalse();
});

it('update a password', function () {
    $modelsEmployee = ModelsEmployee::factory()->create([
        'name' => 'nome 1',
        'email' => 'email1@teste.com',
        'type' => 1,
    ]);

    $employee = new Employee(
        null,
        new Name($modelsEmployee->name),
        new Email($modelsEmployee->email),
        new Password('SenhaAlterada_123'),
        new Type($modelsEmployee->type),
        null
    );

    $modelsPasswordResetToken = ModelsPasswordResetToken::factory([
        'email' => $modelsEmployee->email,
    ])->create();

    $passwordResetToken = new PasswordResetToken(
        new Email($modelsPasswordResetToken->email),
        new Token($modelsPasswordResetToken->token),
        new ExpirationTime($modelsPasswordResetToken->expirationTime)
    );

    $repository = new EloquentEmployeeRepository(
        new EmployeeFactory()
    );
    $updatePassword = $repository->updatePassword($passwordResetToken, $employee, new Token($modelsPasswordResetToken->token));

    expect($updatePassword)->toBeInstanceOf(Employee::class);
    $this->assertNotNull($updatePassword->id());
    expect($updatePassword->name()->value())->toBe($employee->name()->value())
        ->and($updatePassword->email()->value())->toBe($employee->email()->value())
        ->and($updatePassword->type()->value())->toBe($employee->type()->value());
});
