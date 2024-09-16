<?php

use Src\Payments\Application\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Application\Dtos\GetPaymentByIdOutputDto;
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
    $this->repositoryMock = mock(IPaymentRepository::class);
});

it('can retrieve an payments by ID from the repository', function () {
    $notification = new Notification();

    $vehicle = new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-06-02 08:00:00')),
    );

    $paymentId = 1;

    $payment = new Payment(
        $paymentId,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        $vehicle,
        new Notification()
    );

    $this->repositoryMock->shouldReceive('getById')->once()->andReturn($payment);

    $getPaymentById = new GetPaymentById($this->repositoryMock, $notification);

    $inputDto = new GetPaymentByIdInputDto($paymentId);

    $outputDto = $getPaymentById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetPaymentByIdOutputDto::class);
    expect($outputDto->payment)->toBe($payment);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when trying to retrieve a non-existing payment', function () {
    $notification = new Notification();

    $paymentId = 1;

    $this->repositoryMock->shouldReceive('getById')->once()->andReturnNull();

    $getPaymentById = new GetPaymentById($this->repositoryMock, $notification);

    $inputDto = new GetPaymentByIdInputDto($paymentId);

    $outputDto = $getPaymentById->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(GetPaymentByIdOutputDto::class);
    expect($outputDto->payment)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'get_payment_by_id',
            'message' => 'Pagamento não registrado!',
        ],
    ]);
});
