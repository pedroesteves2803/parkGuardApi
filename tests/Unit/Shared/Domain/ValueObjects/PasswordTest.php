<?php

use Src\Shared\Domain\ValueObjects\Password;

test('validates instance password', function () {
    $type = new Password('Password@123');
    expect($type)->toBeInstanceOf(Password::class);
});

it('validates a valid password', function () {
    $password = new Password('Password@123');
    expect($password->value())->toBe('Password@123');
});

it('throws an exception for an empty password', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Password cannot be empty.');
    new Password('');
});

it('throws an exception for a short password', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The password must be at least 8 characters long.');
    new Password('Pass@1');
});

it('throws an exception for a password with no uppercase letter', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The password must contain at least one capital letter.');
    new Password('password@123');
});

it('throws an exception for a password with no lowercase letter', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The password must contain at least one lowercase letter.');
    new Password('PASSWORD@123');
});

it('throws an exception for a password with no number', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The password must contain at least one number.');
    new Password('Password@');
});

it('throws an exception for a password with no special character', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('The password must contain at least one special character.');
    new Password('Password123');
});

it('accepts hashed password and does not validate it', function () {
    $password = new Password('$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe', true);
    expect($password->isHashed())->toBeTrue();
});
