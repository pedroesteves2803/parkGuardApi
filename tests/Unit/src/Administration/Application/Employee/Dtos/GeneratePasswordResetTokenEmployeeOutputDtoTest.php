<?php

use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdOutputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Shared\Utils\Notification;

it('can create an instance of GeneratePasswordResetTokenEmployeeOutputDto with null employee and empty notification', function () {
    $notification = new Notification();

    $passwordResetToken = new PasswordResetToken(
        new Email('email@example.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40))
    );

    $outputDto = new GeneratePasswordResetTokenEmployeeOutputDto($passwordResetToken, $notification);

    expect($outputDto)->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class);
    expect($outputDto->passwordResetToken)->toBe($passwordResetToken);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GeneratePasswordResetTokenEmployeeOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GeneratePasswordResetTokenEmployeeOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GeneratePasswordResetTokenEmployeeOutputDto::class);
    expect($outputDto->passwordResetToken)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
