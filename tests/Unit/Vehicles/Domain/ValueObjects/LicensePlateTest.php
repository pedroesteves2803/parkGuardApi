<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use Exception;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

test('validates instance license plate', function () {
    $licensePlate = new LicensePlate('ABC-1234');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
});

it('throws exception for empty license plate', function () {
    expect(function () {
        new LicensePlate('');
    })->toThrow(\Exception::class, 'LicensePlate cannot be empty.');
});

it('throws exception for invalid license plate', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('It must be a valid license plate.');
    new LicensePlate('INVALID');
});

it('creates LicensePlate instance for valid license plate (Brazilian Standard)', function () {
    $licensePlate = new LicensePlate('ABC-1234');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
    expect($licensePlate->value())->toBe('ABC-1234');
});

it('creates LicensePlate instance for valid license plate (Mercosur Standard)', function () {
    $licensePlate = new LicensePlate('ABC1D23');
    expect($licensePlate)->toBeInstanceOf(LicensePlate::class);
    expect($licensePlate->value())->toBe('ABC1D23');
});
