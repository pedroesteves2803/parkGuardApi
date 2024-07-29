<?php

use Src\Administration\Domain\ValueObjects\Password;

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
    $this->expectExceptionMessage('Senha não pode estar vazio.');
    new Password('');
});

it('throws an exception for a short password', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Senha deve ter pelo menos 8 caracteres.');
    new Password('Pass@1');
});

it('throws an exception for a password with no uppercase letter', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Senha deve conter pelo menos uma letra maiúscula.');
    new Password('password@123');
});

it('throws an exception for a password with no lowercase letter', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Senha deve conter pelo menos uma letra minúscula.');
    new Password('PASSWORD@123');
});

it('throws an exception for a password with no number', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Senha deve conter pelo menos um número.');
    new Password('Password@');
});

it('throws an exception for a password with no special character', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Senha deve conter pelo menos um carácter especial.');
    new Password('Password123');
});

it('accepts hashed password and does not validate it', function () {
    $password = new Password('$2a$12$NhD.F7UP.twqMtPxDo6A8eri.9ESq027PMYoPBonGkZ7uFGO.LaYe', true);
    expect($password->isHashed())->toBeTrue();
});
