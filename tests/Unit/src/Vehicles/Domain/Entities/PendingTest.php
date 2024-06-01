<?php

use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\Type;

test('validates instance pending', function () {
    $pending = createValidPending();
    expect($pending)->toBeInstanceOf(Pending::class);
});

it('validates a valid pending', function () {
    $pending = createValidPending();

    expect($pending->id())->toBe(1);
    expect($pending->type->value())->toBe('Tipo');
    expect($pending->description->value())->toBe('Descrição');
});

function createValidPending()
{
    return new Pending(
        1,
        new Type('Tipo'),
        new Description('Descrição'),
    );
}
