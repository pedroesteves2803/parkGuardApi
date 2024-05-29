<?php

use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdOutputDto;
use Src\Administration\Application\Employee\GetEmployeeById;
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

it('can retrieve an employee by ID from the repository', function () {
    $notification = new Notification();

    $employeeId = 1;

    $employee = new Employee(
        $employeeId,
        new Name('John Doe'),
        new Email('john@example.com'),
        new Password('Password@123'),
        new Type(1),
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($employee);

    $getEmployeeById = new GetEmployeeById($this->repositoryMock, $notification);

    $inputDto = new GetEmployeeByIdInputDto($employeeId);

    $outputDto = $getEmployeeById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBe($employee);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to retrieve a non-existing employee', function () {
    $notification = new Notification();

    $employeeId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $getEmployeeById = new GetEmployeeById($this->repositoryMock, $notification);

    $inputDto = new GetEmployeeByIdInputDto($employeeId);

    $outputDto = $getEmployeeById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'get_employee_by_id',
            'message' => 'Funcionario não encontrado!',
        ],
    ]);
});
