<?php

use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;

it('can create an instance of CreateVehicleInputDto with valid data', function () {
    $inputDto = new CreateVehicleInputDto(
        'Toyota',
        'Azul',
        'Corolla',
        'ABC-1234',
        new DateTime('2024-05-12 08:00:00'),
        null
    );

    expect($inputDto)->toBeInstanceOf(CreateVehicleInputDto::class);
    expect($inputDto->manufacturer)->toBe('Toyota');
    expect($inputDto->color)->toBe('Azul');
    expect($inputDto->model)->toBe('Corolla');
    expect($inputDto->licensePlate)->toBe('ABC-1234');
    expect($inputDto->entryTimes)->toEqual(new DateTime('2024-05-12 08:00:00'));

    expect($inputDto->departureTimes)->toBeNull();
});
