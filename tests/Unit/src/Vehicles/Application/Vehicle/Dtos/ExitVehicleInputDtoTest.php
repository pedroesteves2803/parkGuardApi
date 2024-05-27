<?php

use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

it('can create an instance of ExitVehicleInputDtoTest with valid data', function () {
    $licensePlate = new LicensePlate('ABC-1234');

    $inputDto = new ExitVehicleInputDto($licensePlate);

    expect($inputDto)->toBeInstanceOf(ExitVehicleInputDto::class);
    expect($inputDto->licensePlate)->toBe($licensePlate->value());
});
