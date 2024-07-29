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

test('throws an exception for a value less than 1', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Valor deve ser pelo menos 1.');
    new Value(0);
});
