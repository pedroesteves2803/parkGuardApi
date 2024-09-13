<?php

use DateTime as GlobalDateTime;
use Src\Payments\Application\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Dtos\CreatePaymentOutputDto;
use Src\Payments\Application\Usecase\CalculateValue;
use Src\Payments\Application\Usecase\CreatePayment;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\ExitVehicleOutputDto;
use Src\Vehicles\Application\Dtos\GetVehicleOutputDto;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Service\IVehicleService;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

beforeEach(function () {
    $this->repositoryPaymentMock = mock(IPaymentRepository::class);
    $this->vehicleServiceMock = mock(IVehicleService::class);
});

it('successfully creates a payment', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00')),
    );

    $getVehicleOutputDto = new GetVehicleOutputDto($vehicle, new Notification());
    $this->vehicleServiceMock->shouldReceive('getVehicleById')->once()->andReturn($getVehicleOutputDto);

    $exitVehicleOutputDto = new ExitVehicleOutputDto($vehicle, new Notification());
    $this->vehicleServiceMock->shouldReceive('exitVehicle')->once()->andReturn($exitVehicleOutputDto);

    $expectedPayment = new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        $vehicle
    );

    $this->repositoryPaymentMock
        ->shouldReceive('create')
        ->once()
        ->andReturn($expectedPayment);

    $createPayment = new CreatePayment(
        $this->repositoryPaymentMock,
        new Notification(),
        new CalculateValue(new Notification()),
        $this->vehicleServiceMock
    );

    $inputDto = new CreatePaymentInputDto(
        now(),
        1,
        1
    );

    $outputDto = $createPayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class)
        ->and($outputDto->payment)->toEqual($expectedPayment)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to create a payment with a non-existent vehicle - No vehicle found', function () {
    $notification = new Notification();
    $notification->addError([
        'context' => 'get_vehicle_by_id',
        'message' => 'Veiculo não encontrado!',
    ]);

    $getVehicleOutputDto = new GetVehicleOutputDto(null, $notification);

    $this->vehicleServiceMock->shouldReceive('getVehicleById')
        ->once()
        ->andReturn($getVehicleOutputDto);

    $createPayment = new CreatePayment(
        $this->repositoryPaymentMock,
        new Notification(),
        new CalculateValue(new Notification()),
        $this->vehicleServiceMock
    );

    $inputDto = new CreatePaymentInputDto(
        now(),
        1,
        1
    );

    $outputDto = $createPayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                "context" => "get_vehicle_by_id",
                "message" => "Veiculo não encontrado!"
            ],
        ]);
});

it('fails to create a payment with a non-existent vehicle - Vehicle not registered', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00'))
    );

    $notification = new Notification();

    $this->vehicleServiceMock->shouldReceive('getVehicleById')
        ->once()
        ->andReturn(
            new GetVehicleOutputDto($vehicle, $notification)
        );

    $notification->addError([
        'context' => 'exit_vehicle',
        'message' => 'Veículo não encontrado!',
    ]);

    $createPayment = new CreatePayment(
        $this->repositoryPaymentMock,
        new Notification(),
        new CalculateValue(new Notification()),
        $this->vehicleServiceMock
    );

    $inputDto = new CreatePaymentInputDto(
        now(),
        1,
        1
    );

    $outputDto = $createPayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                "context" => "exit_vehicle",
                "message" => "Veículo não encontrado!"
            ],
        ]);
});
