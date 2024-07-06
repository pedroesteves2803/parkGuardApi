<?php

use Src\Administration\Application\Employee\CreateEmployee;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Application\Employee\GeneratePasswordResetTokenEmployee;
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

it('successfully create token refresh password', function () {
    $notification = new Notification();

    $passwordResetToken = new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40))
    );

    $this->repositoryPasswordMock->shouldReceive('getByEmail')->once()->andReturnNull();
    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturn(
        new Employee(
            1,
            new Name('Nome'),
            new Email('email@test.com'),
            new Password('Password@123'),
            new Type(1),
            null
        )
    );

    $this->repositoryPasswordMock->shouldReceive('create')->once()->andReturn(
        $passwordResetToken
    );

    $generatePasswordResetTokenEmployee = new GeneratePasswordResetTokenEmployee(
        $this->repositoryPasswordMock,
        $this->repositoryEmployeeMock,
        $this->repositorySendPasswordResetTokenMock,
        $notification
    );

    $this->repositorySendPasswordResetTokenMock->shouldReceive('execute')->once()->with(
        $passwordResetToken
    );

    $inputDto = new GeneratePasswordResetTokenEmployeeInputDto('email@test.com');
    $outputDto = $generatePasswordResetTokenEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeInstanceOf(PasswordResetToken::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns an error when employee does not exist', function () {
    $notification = new Notification();

    $this->repositoryPasswordMock->shouldReceive('getByEmail')->once()->andReturnNull();
    $this->repositoryEmployeeMock->shouldReceive('getByEmail')->once()->andReturnNull();

    $generatePasswordResetTokenEmployee = new GeneratePasswordResetTokenEmployee(
        $this->repositoryPasswordMock,
        $this->repositoryEmployeeMock,
        $this->repositorySendPasswordResetTokenMock,
        $notification
    );

    $inputDto = new GeneratePasswordResetTokenEmployeeInputDto('nonexistent@test.com');
    $outputDto = $generatePasswordResetTokenEmployee->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeNull();
    expect($outputDto->notification->getErrors())->not->toBeEmpty();
    expect($outputDto->notification->getErrors())->toContain([
        'context' => 'generate_token_employee',
        'message' => 'Funcionário não existe!'
    ]);
});
