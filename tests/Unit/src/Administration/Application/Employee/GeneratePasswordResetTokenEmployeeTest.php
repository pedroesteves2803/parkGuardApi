<?php

use Src\Administration\Application\Dtos\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Dtos\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Application\Usecase\GeneratePasswordResetTokenEmployee;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryPasswordMock = mock(IPasswordResetRepository::class);
    $this->repositoryEmployeeMock = mock(IEmployeeRepository::class);
    $this->repositorySendPasswordResetTokenMock = mock(ISendPasswordResetTokenService::class);
});

it('successfully creates a password reset token', function () {
    $notification = new Notification();

    $passwordResetToken = new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40))
    );

    $this->repositoryPasswordMock->shouldReceive('getByEmail')
        ->once()
        ->with(Mockery::type(Email::class))
        ->andReturnNull();

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')
        ->once()
        ->with(Mockery::type(Email::class))
        ->andReturn(new Employee(
            1,
            new Name('Nome'),
            new Email('email@test.com'),
            new Password('Password@123'),
            new Type(1),
            null
        ));

    $this->repositoryPasswordMock->shouldReceive('create')
        ->once()
        ->andReturn($passwordResetToken);

    $this->repositorySendPasswordResetTokenMock->shouldReceive('execute')
        ->once()
        ->with(Mockery::type(PasswordResetToken::class)); // Garantir que o tipo está correto

    $generatePasswordResetTokenEmployee = new GeneratePasswordResetTokenEmployee(
        $this->repositoryPasswordMock,
        $this->repositoryEmployeeMock,
        $this->repositorySendPasswordResetTokenMock,
        $notification
    );

    $inputDto = new GeneratePasswordResetTokenEmployeeInputDto('email@test.com');
    $outputDto = $generatePasswordResetTokenEmployee->execute($inputDto);

    expect($outputDto)
        ->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class)
        ->and($outputDto->passwordResetToken)
        ->toBeInstanceOf(PasswordResetToken::class)
        ->and($outputDto->passwordResetToken->token())
        ->not->toBeNull()
        ->and($outputDto->notification->getErrors())
        ->toBeEmpty();
});

it('returns an error when the employee does not exist', function () {
    $notification = new Notification();

    $this->repositoryPasswordMock->shouldReceive('getByEmail')
        ->once()
        ->with(Mockery::type(Email::class))
        ->andReturnNull();

    $this->repositoryEmployeeMock->shouldReceive('getByEmail')
        ->once()
        ->with(Mockery::type(Email::class))
        ->andReturnNull();

    $generatePasswordResetTokenEmployee = new GeneratePasswordResetTokenEmployee(
        $this->repositoryPasswordMock,
        $this->repositoryEmployeeMock,
        $this->repositorySendPasswordResetTokenMock,
        $notification
    );

    $inputDto = new GeneratePasswordResetTokenEmployeeInputDto('nonexistent@test.com');
    $outputDto = $generatePasswordResetTokenEmployee->execute($inputDto);

    expect($outputDto)
        ->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class)
        ->and($outputDto->passwordResetToken)
        ->toBeNull()
        ->and($outputDto->notification->getErrors())
        ->not->toBeEmpty()
        ->and($outputDto->notification->getErrors())
        ->toContain([
            'context' => 'generate_token_employee',
            'message' => 'Funcionário não encontrado.'
        ]);
});
