<?php

use Src\Vehicles\Application\Dtos\UpdateVehicleInputDto;

it('can update an instance of UpdateVehicleInputDto with valid data', function () {
    $id = '1';
    $manufacturer = 'Honda';
    $color = 'Vermelho';
    $model = 'Civic';
    $licensePlate = 'ABC-1234';

    $inputDto = new UpdateVehicleInputDto(
        $id,
        $manufacturer,
        $color,
        $model,
        $licensePlate
    );

    expect($inputDto)->toBeInstanceOf(UpdateVehicleInputDto::class);
    expect($inputDto->id)->toBe($id);
    expect($inputDto->manufacturer)->toBe($manufacturer);
    expect($inputDto->color)->toBe($color);
    expect($inputDto->model)->toBe($model);
    expect($inputDto->licensePlate)->toBe($licensePlate);
});
