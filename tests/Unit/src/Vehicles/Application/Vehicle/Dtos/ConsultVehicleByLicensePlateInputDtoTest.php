<?php

use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;

it('can create an instance of ConsultVehicleByLicensePlateInputDto with valid data', function () {
    $inputDto = new ConsultVehicleByLicensePlateInputDto(
        'ABC1234',
    );

    expect($inputDto)->toBeInstanceOf(ConsultVehicleByLicensePlateInputDto::class);
    expect($inputDto->licensePlate)->toBe('ABC1234');
});
