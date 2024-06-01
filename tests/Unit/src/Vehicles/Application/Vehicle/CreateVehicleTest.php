<?php

use App\Services\SendPendingNotificationService;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\ConsultPendingByLicensePlate;
use Src\Vehicles\Application\Vehicle\CreateVehicle;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;

beforeEach(function () {
    $this->repositoryVehicleMock = mock(IVehicleRepository::class);
    $this->repositoryConsultMock = mock(IConsultVehicleRepository::class);
});

it('successfully creates a vehicle', function () {
    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnFalse();
    $this->repositoryConsultMock->shouldReceive('consult')->once()->andReturn(
        new IConsultVehicleRepositoryOutputDto(
            'Toyota',
            'Azul',
            'Corolla',
            'ABC-1234',
            [
                new Pending(
                    null,
                    new Type('Tipo 1'),
                    new Description('Descrição 1'),
                ),
            ],
        )
    );

    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $vehicle->addPending(
        new Pending(
            null,
            new Type('Tipo 1'),
            new Description('Descrição 1'),
        )
    );

    $this->repositoryVehicleMock
        ->shouldReceive('create')
        ->once()
        ->andReturn($vehicle);

    $createVehicle = new CreateVehicle(
        $this->repositoryVehicleMock,
        new ConsultPendingByLicensePlate(
            $this->repositoryConsultMock,
            new Notification()
        ),
        new SendPendingNotificationService(),
        new Notification()
    );

    $inputDto = new CreateVehicleInputDto(
        'ABC-1234',
    );

    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeInstanceOf(Vehicle::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to create a vehicle with existing license plate', function () {
    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnTrue();
    $createVehicle = new CreateVehicle(
        $this->repositoryVehicleMock,
        new ConsultPendingByLicensePlate(
            $this->repositoryConsultMock,
            new Notification()
        ),
        new SendPendingNotificationService(),
        new Notification()
    );

    $inputDto = new CreateVehicleInputDto(
        'ABC-1234',
    );

    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class);
    expect($outputDto->vehicle)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'create_vehicle',
            'message' => 'Placa já cadastrada!',
        ],
    ]);
});
