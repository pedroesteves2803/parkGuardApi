<?php

use Src\Shared\Domain\ValueObjects\Type;

test('validates instance type', function () {
    $type = new Type(1);
    expect($type)->toBeInstanceOf(Type::class);
});

it('validates a valid type', function () {
    $password = new Type(1);
    expect($password->value())->toBe(1);
});

it('throws an exception for a type other than 1 or 2.', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Type must be 1 or 2.');
    new Type(3);
});
