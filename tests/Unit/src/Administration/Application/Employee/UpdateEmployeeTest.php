<?php

use Src\Administration\Application\Dtos\UpdateEmployeeInputDto;
use Src\Administration\Application\Dtos\UpdateEmployeeOutputDto;
use Src\Administration\Application\Usecase\UpdateEmployee;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryMock = mock(IEmployeeRepository::class);
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

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);

    $this->repositoryMock->shouldReceive('existByEmail')->once()->andReturnFalse();

    $employeeUpdate = new Employee(
        $employeeId,
        new Name('Updated Name'),
        new Email('updated@example.com'),
        new Password('Password@123'),
        new Type(2),
        null
    );

    $this->repositoryMock->shouldReceive('update')->once()->andReturn($employeeUpdate);

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'updated@example.com',
        2,
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBe($employeeUpdate);
    expect($outputDto->notification->getErrors())->toBeEmpty();
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

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);

    $employeeUpdate = new Employee(
        $employeeId,
        new Name('Updated Name'),
        new Email('john@example.com'),
        new Password('NewPassword@456'),
        new Type(2),
        null
    );

    $this->repositoryMock->shouldReceive('update')->once()->andReturn($employeeUpdate);

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'john@example.com',
        2,
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBe($employeeUpdate);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to update an employee with non-existing ID', function () {
    $notification = new Notification();

    $employeeId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'updated@example.com',
        2,
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'update_employee',
            'message' => 'Funcionario não encontrado!',
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

    $updateEmployee = new UpdateEmployee($this->repositoryMock, $notification);

    $inputDto = new UpdateEmployeeInputDto(
        $employeeId,
        'Updated Name',
        'updated@example.com',
        2,
    );

    $outputDto = $updateEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(UpdateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'update_employee',
            'message' => 'Email já cadastrado!',
        ],
    ]);
});
