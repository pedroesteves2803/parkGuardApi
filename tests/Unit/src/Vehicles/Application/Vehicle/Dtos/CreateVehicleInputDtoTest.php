<?php

use Src\Vehicles\Application\Dtos\CreateVehicleInputDto;

it('can create an instance of CreateVehicleInputDto with valid data', function () {
    $inputDto = new CreateVehicleInputDto(
        'ABC-1234',
    );

    expect($inputDto)->toBeInstanceOf(CreateVehicleInputDto::class);
    expect($inputDto->licensePlate)->toBe('ABC-1234');
});
