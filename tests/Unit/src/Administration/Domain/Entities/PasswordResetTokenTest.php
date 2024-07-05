<?php

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;

test('validates instance of PasswordResetToken', function () {
    $passwordResetToken = createValidPasswordResetToken();
    expect($passwordResetToken)->toBeInstanceOf(PasswordResetToken::class);
});

it('validates a valid password reset token', function () {
    $passwordResetToken = createValidPasswordResetToken();
    expect($passwordResetToken->email()->value())->toBe('email@test.com');
    expect($passwordResetToken->token()->value())->toBe('token');
    expect($passwordResetToken->expirationTime()->value())->not()->toBeNull();
});

it('generates a token if none is provided', function () {
    $passwordResetToken = new PasswordResetToken(
        new Email('email@test.com'),
        null,
        new ExpirationTime(now()->addMinutes(40))
    );
    expect($passwordResetToken->token()->value())->toHaveLength(5);
});

it('returns true if the token is expired', function () {
    $expiredToken = new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->subMinutes(1))
    );
    expect($expiredToken->isExpired())->toBeTrue();
});

it('returns false if the token is not expired', function () {
    $validToken = new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(10))
    );
    expect($validToken->isExpired())->toBeFalse();
});

function createValidPasswordResetToken()
{
    return new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        new ExpirationTime(now()->addMinutes(40)),
    );
}
