<?php

use DateTime as GlobalDateTime;
use Src\Payments\Application\Payment\CreatePayment;
use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\ExistVehicleById;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

beforeEach(function () {
    $this->repositoryPaymentMock = mock(IPaymentRepository::class);
    $this->repositoryVehicleMock = mock(IVehicleRepository::class);
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

    $this->repositoryVehicleMock->shouldReceive('getById')->once()->andReturn($vehicle);
    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnTrue();
    $this->repositoryVehicleMock->shouldReceive('exit')->once()->andReturn($vehicle);

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
        new GetVehicleById(
            $this->repositoryVehicleMock,
            new Notification()
        ),
        new ExitVehicle(
            $this->repositoryVehicleMock,
            new Notification(),
            new ExistVehicleById(
                $this->repositoryVehicleMock,
                new Notification(),
            )
        ),
        new Notification()
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
    $this->repositoryVehicleMock->shouldReceive('getById')->once()->andReturnNull();

    $createPayment = new CreatePayment(
        $this->repositoryPaymentMock,
        new GetVehicleById(
            $this->repositoryVehicleMock,
            new Notification()
        ),
        new ExitVehicle(
            $this->repositoryVehicleMock,
            new Notification(),
            new ExistVehicleById(
                $this->repositoryVehicleMock,
                new Notification(),
            )
        ),
        new Notification()
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
                'context' => 'create_payment',
                'message' => 'Veículo não cadastrado!',
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
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00')),
    );

    $this->repositoryVehicleMock->shouldReceive('getById')->once()->andReturn($vehicle);
    $this->repositoryVehicleMock->shouldReceive('existVehicle')->once()->andReturnFalse();

    $createPayment = new CreatePayment(
        $this->repositoryPaymentMock,
        new GetVehicleById(
            $this->repositoryVehicleMock,
            new Notification()
        ),
        new ExitVehicle(
            $this->repositoryVehicleMock,
            new Notification(),
            new ExistVehicleById(
                $this->repositoryVehicleMock,
                new Notification(),
            )
        ),
        new Notification()
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
                'context' => 'create_payment',
                'message' => 'Veículo não cadastrado!',
            ],
        ]);
});
