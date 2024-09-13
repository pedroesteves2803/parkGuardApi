<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Dtos\CreateVehicleOutputDto;
use Src\Vehicles\Application\Usecase\ConsultPendingByLicensePlate;
use Src\Vehicles\Application\Usecase\CreateVehicle;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Factory\VehicleFactory;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;

beforeEach(function () {
    $this->repositoryVehicleMock = Mockery::mock(IVehicleRepository::class);
    $this->repositoryConsultMock = Mockery::mock(IConsultVehicleRepository::class);
    $this->sendPendingNotificationServiceMock = Mockery::mock(ISendPendingNotificationService::class);
    $this->notification = new Notification();
});

it('successfully creates a vehicle', function () {
    $this->sendPendingNotificationServiceMock->shouldReceive('execute')->andReturn(null);
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
            $this->notification
        ),
        $this->sendPendingNotificationServiceMock,
        $this->notification,
        new VehicleFactory()
    );

    $inputDto = new CreateVehicleInputDto('ABC-1234');
    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBeInstanceOf(Vehicle::class)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to create a vehicle with existing license plate', function () {
    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnTrue();

    $createVehicle = new CreateVehicle(
        $this->repositoryVehicleMock,
        new ConsultPendingByLicensePlate(
            $this->repositoryConsultMock,
            $this->notification
        ),
        $this->sendPendingNotificationServiceMock,
        $this->notification,
        new VehicleFactory()
    );

    $inputDto = new CreateVehicleInputDto('ABC-1234');
    $outputDto = $createVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreateVehicleOutputDto::class)
        ->and($outputDto->vehicle)->toBeNull()
        ->and($outputDto->notification->getErrors())->toContain([
            'context' => 'create_vehicle',
            'message' => 'Placa já cadastrada!',
        ]);
});
