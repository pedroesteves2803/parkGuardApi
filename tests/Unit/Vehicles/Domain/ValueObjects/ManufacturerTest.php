<?php

use Src\Vehicles\Domain\ValueObjects\Manufacturer;

test('validates instance manufacturer', function () {
    $manufacturer = new Manufacturer('Honda');
    expect($manufacturer)->toBeInstanceOf(Manufacturer::class);
});

it('validates a valid manufacturer', function () {
    $manufacturer = new Manufacturer('Honda');
    expect($manufacturer->value())->toBe('Honda');
});

test('throws an exception for an empty manufacturer', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Manufacturer cannot be empty');
    new Manufacturer('');
});

test('test manufacturer object to string conversion', function () {
    $manufacturer = new Manufacturer('Honda');
    expect((string) $manufacturer)->toBe('Honda');
});
