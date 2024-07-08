<?php

use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\UpdateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\VerifyTokenPasswordResetOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can update an instance of VerifyTokenPasswordResetOutputDto with a valid employee', function () {
    $token = 'token';
    $email = 'john@example.com';
    $dataExpiration = now()->addMinutes(10);

    $passwordResetToken = new PasswordResetToken(
        new Email($email),
        new Token($token),
        new ExpirationTime($dataExpiration),
    );

    $notification = new Notification();

    $outputDto = new VerifyTokenPasswordResetOutputDto($passwordResetToken, $notification);

    expect($outputDto)->toBeInstanceOf(VerifyTokenPasswordResetOutputDto::class);
    expect($outputDto->passwordResetToken)->toBe($passwordResetToken);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can update an instance of VerifyTokenPasswordResetOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new VerifyTokenPasswordResetOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(VerifyTokenPasswordResetOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
