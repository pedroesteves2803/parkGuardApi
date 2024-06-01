<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use Exception;
use Src\Vehicles\Domain\ValueObjects\Description;

test('validates instance description', function () {
    $description = new Description('Descrição');
    expect($description)->toBeInstanceOf(Description::class);
});

it('valid description', function () {
    $description = new Description('Descrição');
    expect($description->value())->toBe('Descrição');
});

test('throws an exception for an empty description', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Description cannot be empty.');
    new Description('');
});
