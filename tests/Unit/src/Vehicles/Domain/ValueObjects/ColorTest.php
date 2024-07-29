<?php

use Src\Vehicles\Domain\ValueObjects\Color;

test('validates instance color', function () {
    $color = new Color('blue');
    expect($color)->toBeInstanceOf(Color::class);
});

it('validates a valid color', function () {
    $color = new Color('blue');
    expect($color->value())->toBe('blue');
});

test('throws an exception for an empty color', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Cor não pode estar vazia.');
    new Color('');
});

test('test color object to string conversion', function () {
    $color = new Color('blue');
    expect((string) $color)->toBe('blue');
});
