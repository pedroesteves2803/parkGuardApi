<?php

use Src\Administration\Application\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Usecase\CreateEmployee;
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

it('successfully creates an employee', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existByEmail')->once()->andReturnFalse();
    $this->repositoryMock->shouldReceive('create')->once()->andReturn(
        new Employee(
            1,
            new Name('Nome'),
            new Email('email@test.com'),
            new Password('Password@123'),
            new Type(1),
            null
        )
    );

    $createEmployee = new CreateEmployee($this->repositoryMock, $notification);

    $inputDto = new CreateEmployeeInputDto('Nome', 'email@test.com', 'Password@123', 1);
    $outputDto = $createEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeInstanceOf(Employee::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to create an employee with existing email', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existByEmail')->once()->andReturnTrue();
    $createEmployee = new CreateEmployee($this->repositoryMock, $notification);

    $inputDto = new CreateEmployeeInputDto('Nome', 'email@test.com', 'Password@123', 1);
    $outputDto = $createEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'create_employee',
            'message' => 'Email já cadastrado!',
        ],
    ]);
});
