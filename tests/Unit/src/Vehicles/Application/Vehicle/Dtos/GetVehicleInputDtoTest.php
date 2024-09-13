<?php

use Src\Vehicles\Application\Dtos\GetVehicleInputDto;

it('can create an instance of GetVehicleInputDto with a valid ID', function () {
    $id = 1;

    $inputDto = new GetVehicleInputDto($id);

    expect($inputDto)->toBeInstanceOf(GetVehicleInputDto::class);
    expect($inputDto->id)->toBe($id);
});
