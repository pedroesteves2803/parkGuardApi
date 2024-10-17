<?php

use Src\Administration\Application\Dtos\Employee\LoginEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\LoginEmployeeOutputDto;
use Src\Administration\Application\Usecase\Employee\LoginEmployee;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryMock = mock(ILoginEmployeeService::class);
});

it('successfully login an employee', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('login')->once()->andReturn(
        new Employee(
            1,
            new Name('Nome'),
            new Email('email@test.com'),
            new Password('Password@123'),
            new Type(1),
            'token'
        )
    );

    $loginEmployee = new LoginEmployee($this->repositoryMock, $notification);

    $inputDto = new LoginEmployeeInputDto('email@test.com', 'Password@123');
    $outputDto = $loginEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(LoginEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeInstanceOf(Employee::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to login an employee', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('login')->once()->andReturn(null);

    $loginEmployee = new LoginEmployee($this->repositoryMock, $notification);

    $inputDto = new LoginEmployeeInputDto('email@test.com', 'Password@123');
    $outputDto = $loginEmployee->execute($inputDto);


    expect($outputDto)->toBeInstanceOf(LoginEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'login_employee',
            'message' => 'Email ou senha incorretos!',
        ],
    ]);
});
