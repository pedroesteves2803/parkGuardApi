<?php

use Src\Vehicles\Domain\ValueObjects\Model;

test('validates instance model', function () {
    $model = new Model('Golf');
    expect($model)->toBeInstanceOf(Model::class);
});

it('validates a valid model', function () {
    $model = new Model('Golf');
    expect($model->value())->toBe('Golf');
});

test('throws an exception for an empty model', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Modelo não pode estar vazio.');
    new Model('');
});

test('test model object to string conversion', function () {
    $model = new Model('Golf');
    expect((string) $model)->toBe('Golf');
});
