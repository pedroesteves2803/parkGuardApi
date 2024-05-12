<?php

use Src\Administration\Application\Employee\DeleteEmployeeById;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdOutputDto;
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

it('can delete an existing employee by ID', function () {
    $notification = new Notification();

    $employeeId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn(
        new Employee(
            $employeeId,
            new Name('John Doe'),
            new Email('john@example.com'),
            new Password('Password@123'),
            new Type(1),
        )
    );

    $this->repositoryMock->shouldReceive('delete')
        ->with($employeeId);

    $deleteEmployeeById = new DeleteEmployeeById($this->repositoryMock, $notification);

    $inputDto = new DeleteEmployeeByIdInputDto($employeeId);

    $outputDto = $deleteEmployeeById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(DeleteEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to delete a non-existing employee', function () {
    $notification = new Notification();

    $employeeId = 100;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $deleteEmployeeById = new DeleteEmployeeById($this->repositoryMock, $notification);

    $inputDto = new DeleteEmployeeByIdInputDto($employeeId);

    $outputDto = $deleteEmployeeById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(DeleteEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'employee_not_found',
            'message' => 'Funcionario não encontrado!',
        ],
    ]);
});
