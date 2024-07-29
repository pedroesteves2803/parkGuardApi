<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use Src\Vehicles\Domain\ValueObjects\LicensePlate;

test('validates instance license plate', function () {
    $licensePlate = new LicensePlate('ABC-1234');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
});

it('throws exception for empty license plate', function () {
    expect(function () {
        new LicensePlate('');
    })->toThrow(\Exception::class, 'Placa não pode estar vazia.');
});

it('throws exception for invalid license plate', function () {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Placa deve ser válida.');
    new LicensePlate('INVALID');
});

it('creates LicensePlate instance for valid license plate (Brazilian Standard)', function () {
    $licensePlate = new LicensePlate('ABC-1234');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
    expect($licensePlate->value())->toBe('ABC1234');
});

it('creates LicensePlate instance for valid license plate (Mercosur Standard)', function () {
    $licensePlate = new LicensePlate('ABC1D23');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
    expect($licensePlate->value())->toBe('ABC1D23');
});
