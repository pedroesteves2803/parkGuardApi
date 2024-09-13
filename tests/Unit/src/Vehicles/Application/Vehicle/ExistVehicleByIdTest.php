<?php

use Src\Vehicles\Application\Dtos\ExistVehicleInputDto;
use Src\Vehicles\Application\Usecase\ExistVehicleById;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

beforeEach(function () {
    $this->repositoryVehicleMock = mock(IVehicleRepository::class);
});

test('should check if there is a vehicle by returning false.', function () {

    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnFalse();

    $inputDto = new ExistVehicleInputDto(
        new \Src\Vehicles\Domain\ValueObjects\LicensePlate('ABC-1234')
    );

    $existVehicleById = new ExistVehicleById(
        $this->repositoryVehicleMock,
        new \Src\Shared\Utils\Notification()
    );

    $outputDto = $existVehicleById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(\Src\Vehicles\Application\Dtos\ExistVehicleOutputDto::class)
        ->and($outputDto->notification->getErrors())->toBeEmpty()
        ->and($outputDto->exist)->toBeFalse();
});
