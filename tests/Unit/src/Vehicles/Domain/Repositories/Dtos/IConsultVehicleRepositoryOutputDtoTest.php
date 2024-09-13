<?php

use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\Type;

it('can create an instance of IConsultVehicleRepositoryOutputDto with valid data', function () {
    $inputDto = new IConsultVehicleRepositoryOutputDto(
        'Honda',
        'Azul',
        'Civic',
        'ABC-1234',
        [
            new Pending(
                null,
                new Type('Tipo'),
                new Description('Tipo'),
            )
        ],
    );

    expect($inputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    expect($inputDto->manufacturer)->toBe('Honda');
    expect($inputDto->color)->toBe('Azul');
    expect($inputDto->model)->toBe('Civic');
    expect($inputDto->licensePlate)->toBe('ABC-1234');

    $expectedPendings = [
        new Pending(
            null,
            new Type('Tipo'),
            new Description('Tipo'),
        )
    ];

    expect(count($inputDto->pending))->toBe(count($expectedPendings));

    foreach ($inputDto->pending as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }
});
