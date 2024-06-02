<?php

use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Vehicles\Domain\Entities\Vehicle;

test('validates instance payment', function () {
    $payment = createValidPayment();
    expect($payment)->toBeInstanceOf(Payment::class);
});

it('validates a valid employee', function () {
    $payment = createValidPayment();
    expect($payment->id())->toBe(1);
    expect($payment->value()->value())->toBe(1000);
    expect($payment->dateTime()->value())->not->toBeNull();
    expect($payment->paymentMethod()->value())->toBe(1);
    expect($payment->vehicle())->not->toBeNull();
});

function createValidPayment()
{
    return new Payment(
        1,
        new Value(1000),
        new DateTime(now()),
        new PaymentMethod(1),
        false,
        mock(Vehicle::class)
    );
}
