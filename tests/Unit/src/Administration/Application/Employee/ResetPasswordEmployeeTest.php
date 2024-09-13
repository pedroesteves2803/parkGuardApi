<?php

use Src\Administration\Application\Dtos\PasswordResetEmployeeInputDto;
use Src\Administration\Application\Dtos\PasswordResetEmployeeOutputDto;
use Src\Administration\Application\Usecase\ResetPasswordEmployee;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryEmployeeMock = mock(IEmployeeRepository::class);
    $this->repositoryPasswordResetMock = mock(IPasswordResetRepository::class);
});

it('can reset the password for an employee with valid input', function () {
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

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturn(
        new PasswordResetToken(
            $employee->email(),
            new Token('token'),
            new ExpirationTime(now()->addMinutes(40))
        )
    );

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturn($employee);

    $employeeUpdate = new Employee(
        $employeeId,
        new Name('Updated Name'),
        new Email('updated@example.com'),
        new Password('NewPassword@456'),
        new Type(2),
        null
    );

    $this->repositoryEmployeeMock->shouldReceive('updatePassword')->once()->andReturn($employeeUpdate);

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'token',
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBe($employeeUpdate);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns an error notification if the token does not exist', function () {
    $notification = new Notification();

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturnNull();

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'token',
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'password_reset_employee',
            'message' => 'Token não existe!',
        ],
    ]);
});

it('returns an error notification if the employee does not exist', function () {
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

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturn(
        new PasswordResetToken(
            $employee->email(),
            new Token('token'),
            new ExpirationTime(now()->addMinutes(40))
        )
    );

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturnNull();

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'token',
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'password_reset_employee',
            'message' => 'Funcionario não encontrado!',
        ],
    ]);
});

it('returns an error notification if the token is expired', function () {
    $notification = new Notification();

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')
        ->once()
        ->andReturn(
            new PasswordResetToken(
                new Email('john@example.com'),
                new Token('token'),
                new ExpirationTime(now()->subHour(1))  // 1 hora atrás
            )
        );

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')
        ->never();

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'token'
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'password_reset_employee',
            'message' => 'Token expirado!',
        ],
    ]);
});

it('throws an exception if the reset token does not match the token in the input', function () {
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

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturn(
        new PasswordResetToken(
            $employee->email(),
            new Token('abcde'),
            new ExpirationTime(now()->addMinutes(40))
        )
    );

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturn($employee);

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'fghij',
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'password_reset_employee',
            'message' => 'Token de redefinição de senha não encontrado ou inválido.',
        ],
    ]);
});

it('throws an exception if the updatePassword method returns null', function () {
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

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturn(
        new PasswordResetToken(
            $employee->email(),
            new Token('token'),
            new ExpirationTime(now()->addMinutes(40))
        )
    );

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturn($employee);

    $this->repositoryEmployeeMock->shouldReceive('updatePassword')->once()->andReturnNull();

    $resetPasswordEmployee = new ResetPasswordEmployee($this->repositoryPasswordResetMock, $this->repositoryEmployeeMock, $notification);

    $inputDto = new PasswordResetEmployeeInputDto(
        'NewPassword@456',
        'token',
    );

    $outputDto = $resetPasswordEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'password_reset_employee',
            'message' => 'Token de redefinição de senha não encontrado ou inválido.',
        ],
    ]);
});
