<?php

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;

test('validates instance password reset token', function () {
    $passwordResetToken = createValidPasswordResetToken();
    expect($passwordResetToken)->toBeInstanceOf(PasswordResetToken::class);
});

it('validates a valid password reset token', function () {
    $passwordResetToken = createValidPasswordResetToken();
    expect($passwordResetToken->email()->value())->toBe('email@test.com');
    expect($passwordResetToken->token()->value())->toBe('token');
    expect($passwordResetToken->expirationTime()->value())->not()->toBeNull();

});

function createValidPasswordResetToken()
{
    return new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40)),
    );
}
