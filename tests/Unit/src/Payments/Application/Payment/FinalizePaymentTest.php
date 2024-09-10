<?php

use DateTime as GlobalDateTime;
use Src\Payments\Application\Payment\DeletePaymentById;
use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentOutputDto;
use Src\Payments\Application\Payment\FinalizePayment;
use Src\Payments\Application\Payment\GetPaymentById;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

beforeEach(function () {
    $this->repositoryPaymentMock = mock(IPaymentRepository::class);
});

it('successfully finalize a payment', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00')),
    );

    $payment = new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        $vehicle
    );

    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturn($payment);

    $payment = new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        true,
        $vehicle
    );

    $this->repositoryPaymentMock
    ->shouldReceive('finalize')
    ->once()
    ->andReturn(
        $payment
    );

    $finalizePayment = new FinalizePayment(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new FinalizePaymentInputDto(
        1,
    );

    $outputDto = $finalizePayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(FinalizePaymentOutputDto::class)
        ->and($outputDto->payment)->toBe($payment)
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('fails to finalize non-existent payment', function () {
    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturnNull();

    $finalizePayment = new FinalizePayment(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new FinalizePaymentInputDto(
        1,
    );

    $outputDto = $finalizePayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(FinalizePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'get_payment_by_id',
                'message' => 'Pagamento não registrado!',
            ],
        ]);
});

it('fails to delete payment already finalized', function () {
    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00')),
    );

    $payment = new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        $vehicle
    );

    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturn($payment);

    $payment = new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        true,
        $vehicle
    );

    $this->repositoryPaymentMock
        ->shouldReceive('finalize')
        ->once()
        ->andReturnNull();

    $finalizePayment = new FinalizePayment(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new FinalizePaymentInputDto(
        1,
    );

    $outputDto = $finalizePayment->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(FinalizePaymentOutputDto::class);
    expect($outputDto->payment)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'finalize_payment',
            'message' => 'Pagamento já foi finalizado!',
        ],
    ]);
});
