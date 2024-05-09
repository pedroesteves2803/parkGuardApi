<?php

use Src\Administration\Domain\ValueObjects\Name;

test('validates instance name', function () {
    $name = new Name('Produto 1');
    expect($name)->toBeInstanceOf(Name::class);
});

it('validates a valid name', function () {
    $name = new Name('Produto 1');
    expect($name->value())->toBe('Produto 1');
});

test('throws an exception for the empty name', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Name cannot be empty.');
    new Name('');
});

test('test Name object to string conversion', function () {
    $name = new Name('Produto 1');
    $expectedString = 'Produto 1';
    expect((string) $name)->toBe($expectedString);
});
