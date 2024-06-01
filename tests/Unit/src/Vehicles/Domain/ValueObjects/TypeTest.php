<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use Exception;
use Src\Vehicles\Domain\ValueObjects\Type;

test('validates instance type', function () {
    $description = new Type('Tipo');
    expect($description)->toBeInstanceOf(Type::class);
});

it('valid type', function () {
    $description = new Type('Tipo');
    expect($description->value())->toBe('Tipo');
});

test('throws an exception for an empty type', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Type cannot be empty.');
    new Type('');
});
