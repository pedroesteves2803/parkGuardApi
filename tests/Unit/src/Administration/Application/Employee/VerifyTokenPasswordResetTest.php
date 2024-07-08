<?php

use Src\Administration\Application\Employee\Dtos\VerifyTokenPasswordResetInputDto;
use Src\Administration\Application\Employee\Dtos\VerifyTokenPasswordResetOutputDto;
use Src\Administration\Application\Employee\VerifyTokenPasswordReset;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryPasswordResetMock = mock(IPasswordResetRepository::class);
});

it('verifies a valid token for password reset', function () {
    $notification = new Notification();

    $passwordResetToken = new PasswordResetToken(
        new Email('email@example.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40)),
    );

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->once()->andReturn(
        $passwordResetToken
    );

    $verifyTokenPasswordReset = new VerifyTokenPasswordReset($this->repositoryPasswordResetMock, $notification);

    $inputDto = new VerifyTokenPasswordResetInputDto(
        'token',
    );

    $outputDto = $verifyTokenPasswordReset->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(VerifyTokenPasswordResetOutputDto::class);
    expect($outputDto->passwordResetToken)->toBe($passwordResetToken);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns an error if the token does not exist', function () {
    $notification = new Notification();

    $this->repositoryPasswordResetMock->shouldReceive('getByToken')->andReturnNull();

    $verifyTokenPasswordReset = new VerifyTokenPasswordReset($this->repositoryPasswordResetMock, $notification);

    $inputDto = new VerifyTokenPasswordResetInputDto(
        'token',
    );

    $outputDto = $verifyTokenPasswordReset->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(VerifyTokenPasswordResetOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'verify_token_password_reset_employee',
            'message' => 'Token não existe!',
        ],
    ]);
});

it('returns an error if the token is expired', function () {
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

    $verifyTokenPasswordReset = new VerifyTokenPasswordReset($this->repositoryPasswordResetMock, $notification);

    $inputDto = new VerifyTokenPasswordResetInputDto(
        'token',
    );

    $outputDto = $verifyTokenPasswordReset->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(VerifyTokenPasswordResetOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'verify_token_password_reset_employee',
            'message' => 'Token expirado!',
        ],
    ]);
});
