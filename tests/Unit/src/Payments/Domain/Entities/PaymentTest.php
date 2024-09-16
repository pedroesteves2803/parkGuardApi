<?php

use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Vehicles\Domain\Entities\Vehicle;
use Mockery\MockInterface;
use Carbon\Carbon;

test('validates instance of Payment', function () {
    $payment = createValidPayment();
    expect($payment)->toBeInstanceOf(Payment::class);
});

it('validates a valid payment entity', function () {
    $payment = createValidPayment();

    expect($payment->id())->toBe(1)
        ->and($payment->value()->value())->toBe(1000)
        ->and($payment->registrationTime()->value())->not->toBeNull()
        ->and($payment->paymentMethod()->value())->toBe(1)
        ->and($payment->vehicle())->not->toBeNull();
});

it('calculates total to pay correctly for less than 1 hour', function () {
    $payment = createPaymentWithVehicleTimes('2024-09-15 14:00:00', '2024-09-15 14:30:00');

    $payment->calculateTotalToPay();

    expect($payment->value()->value())->toBe(2000);
});

it('calculates total to pay correctly for more than 1 hour', function () {
    $payment = createPaymentWithVehicleTimes('2024-09-15 14:00:00', '2024-09-15 16:30:00');

    $payment->calculateTotalToPay();

    expect($payment->value()->value())->toBe(4000); // 2 horas = 2000 + 1000
});

it('throws exception when departure time is null', function () {
    $payment = createPaymentWithoutDepartureTime();

    expect(static fn() => $payment->calculateTotalToPay())->toThrow(RuntimeException::class, 'Horário de partida não está definido!');
});

function createValidPayment(): Payment
{
    return new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        mockVehicleWithTimes('2024-09-15 14:00:00', '2024-09-15 15:00:00'),
        new \Src\Shared\Utils\Notification()
    );
}

function createPaymentWithVehicleTimes(string $entryTime, string $departureTime): Payment
{
    return new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        mockVehicleWithTimes($entryTime, $departureTime),
        new \Src\Shared\Utils\Notification()
    );
}

function createPaymentWithoutDepartureTime(): Payment
{
    return new Payment(
        1,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        mockVehicleWithoutDepartureTime('2024-09-15 14:00:00'),
        new \Src\Shared\Utils\Notification()
    );
}

function mockVehicleWithTimes(string $entryTime, string $departureTime): Vehicle
{
    return mock(Vehicle::class, static function (MockInterface $mock) use ($entryTime, $departureTime) {
        $mock->shouldReceive('entryTimes->value')->andReturn(new Carbon($entryTime));
        $mock->shouldReceive('departureTimes->value')->andReturn(new Carbon($departureTime));
    });
}

function mockVehicleWithoutDepartureTime(string $entryTime): Vehicle
{
    return mock(Vehicle::class, static function (MockInterface $mock) use ($entryTime) {
        $mock->shouldReceive('entryTimes->value')->andReturn(new Carbon($entryTime));
        $mock->shouldReceive('departureTimes')->andReturn(null);
    });
}
