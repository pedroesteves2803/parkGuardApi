<?php

use Src\Payments\Domain\ValueObjects\Value;

test('validates instance value', function () {
    $value = new Value(1000);
    expect($value)->toBeInstanceOf(Value::class);
});

it('validates a valid value', function () {
    $value = new Value(1000);
    expect($value->value())->toBe(1000);
});

test('throws an exception for a value less than 0', function () {
    $this->expectException(\OutOfRangeException::class);
    $this->expectExceptionMessage('Valor deve ser pelo menos 0.');
    new Value(-1);
});
