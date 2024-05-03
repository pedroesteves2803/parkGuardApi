<?php

use Src\Shared\Domain\ValueObjects\Email;

test('validates instance email', function () {
    $email = new Email("email@test.com");
    expect($email)->toBeInstanceOf(Email::class);
});

it('validates a valid email', function () {
    $email = new Email("email@test.com");
    expect($email->value())->toBe('email@test.com');
});

test('throws an exception for an empty email', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Email cannot be empty.');
    new Email("");
});

test('throws an exception for invalid email', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Email not valid.');
    new Email("invalid.com");
});

test('test email object to string conversion', function () {
    $email = new Email("email@test.com");
    expect((string)$email)->toBe("email@test.com");
});
