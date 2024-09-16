<?php

use DateTime as GlobalDateTime;
use Src\Payments\Application\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Dtos\DeletePaymentOutputDto;
use Src\Payments\Application\Usecase\DeletePaymentById;
use Src\Payments\Application\Usecase\GetPaymentById;
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

it('successfully deletes a payment', function () {
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
        $vehicle,
        new Notification()
    );

    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturn($payment);

    $this->repositoryPaymentMock
        ->shouldReceive('delete')
        ->once()
        ->andReturn();

    $deletePaymentById = new DeletePaymentById(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new DeletePaymentInputDto(
        1,
    );

    $outputDto = $deletePaymentById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(DeletePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBeEmpty();
});

it('cannot delete a non-existent payment', function () {

    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturnNull();

    $deletePaymentById = new DeletePaymentById(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new DeletePaymentInputDto(
        1,
    );

    $outputDto = $deletePaymentById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(DeletePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'get_payment_by_id',
                'message' => 'Pagamento não registrado!',
            ],
        ]);
});

it('cannot delete a payment that has already been paid', function () {

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
        true,
        $vehicle,
        new Notification()
    );

    $this->repositoryPaymentMock->shouldReceive('getById')->once()->andReturn($payment);

    $deletePaymentById = new DeletePaymentById(
        $this->repositoryPaymentMock,
        new GetPaymentById(
            $this->repositoryPaymentMock,
            new Notification()
        ),
        new Notification()
    );

    $inputDto = new DeletePaymentInputDto(
        1,
    );

    $outputDto = $deletePaymentById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(DeletePaymentOutputDto::class)
        ->and($outputDto->payment)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'delete_payment',
                'message' => 'Este pagamento não pode ser excluído porque já foi registrado como pago.',
            ],
        ]);
});
