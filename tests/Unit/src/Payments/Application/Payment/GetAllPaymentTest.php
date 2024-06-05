<?php

use DateTime as GlobalDateTime;
use Illuminate\Support\Collection;
use Src\Payments\Application\Payment\DeletePaymentById;
use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\GetAllPaymentsOutputDto;
use Src\Payments\Application\Payment\FinalizePayment;
use Src\Payments\Application\Payment\GetAllPayment;
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

it('can retrieve all payments', function () {
    $payments = new Collection();

    $vehicle = new Vehicle(
        null,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new GlobalDateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new GlobalDateTime('2024-06-02 08:00:00')),
    );

    $payments->push(
        new Payment(
            1,
            new Value(1000),
            new RegistrationTime(now()),
            new PaymentMethod(1),
            false,
            $vehicle
        )
    );

    $this->repositoryPaymentMock->shouldReceive('getAll')->once()->andReturn($payments);

    $getAllPayment = new GetAllPayment(
        $this->repositoryPaymentMock,
        new Notification()
    );

    $outputDto = $getAllPayment->execute();

    expect($outputDto)->toBeInstanceOf(GetAllPaymentsOutputDto::class);
    expect($outputDto->payments)->toBe($payments);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when there are no payments', function () {
    $this->repositoryPaymentMock->shouldReceive('getAll')->once()->andReturnNull();

    $getAllPayment = new GetAllPayment(
        $this->repositoryPaymentMock,
        new Notification()
    );

    $outputDto = $getAllPayment->execute();

    expect($outputDto)->toBeInstanceOf(GetAllPaymentsOutputDto::class);
    expect($outputDto->payments)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'get_all_payments',
            'message' => 'Não possui pagamentos!',
        ],
    ]);
});
