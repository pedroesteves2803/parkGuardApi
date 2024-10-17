<?php

use Src\Administration\Application\Dtos\Employee\UpdateEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\UpdateEmployeeOutputDto;
use Src\Administration\Application\Usecase\Employee\UpdateEmployee;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Factory\EmployeeFactory;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryMock = mock(IEmployeeRepository::class);
    $this->factoryMock = mock(EmployeeFactory::class);
});

it('can update an existing employee with valid input', function () {
    $notification = new Notification();

    $employeeId = 1;

    $employee = new Employee(
        $employeeId,
        new Name('John Doe'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );

    $updatedEmployee = new Employee(
        $employeeId,
        new Name('Updated Name'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(2),
        null
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
    $this->repositoryMock->shouldReceive('update')->once()->andReturn($updatedEmployee);

    $this->factoryMock->shouldReceive('create')->once()->andReturn($updatedEmployee);

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification, $this->factoryMock);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'john@example.com',
        2
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class)
        ->and($outputDto->employee)->toBe($updatedEmployee)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('can update an existing employee with a valid entry and the same email as the employee sent', function () {
    $notification = new Notification();

    $employeeId = 1;

    $employee = new Employee(
        $employeeId,
        new Name('John Doe'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );

    $updatedEmployee = new Employee(
        $employeeId,
        new Name('Updated Name'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(2),
        null
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
    $this->repositoryMock->shouldReceive('update')->once()->andReturn($updatedEmployee);

    $this->factoryMock->shouldReceive('create')->once()->andReturn($updatedEmployee);

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification, $this->factoryMock);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'john@example.com',
        2
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class)
        ->and($outputDto->employee)->toBe($updatedEmployee)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to update an employee with non-existing ID', function () {
    $notification = new Notification();

    $employeeId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification, $this->factoryMock);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'updated@example.com',
        2
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class)
        ->and($outputDto->employee)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'update_employee',
                'message' => 'Funcionário não encontrado.',
            ],
        ]);
});

it('returns error notification when trying to update an employee with an already existing email', function () {
    $notification = new Notification();

    $employeeId = 1;

    $employee = new Employee(
        $employeeId,
        new Name('John Doe'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);
    $this->repositoryMock->shouldReceive('existByEmail')->once()->andReturnTrue();

    $this->factoryMock->shouldNotReceive('create');

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification, $this->factoryMock);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'updated@example.com',
        2
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class)
        ->and($outputDto->employee)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'update_employee',
                'message' => 'Email já cadastrado!',
            ],
        ]);
});
