<?php

use Src\Payments\Domain\ValueObjects\PaymentMethod;

test('validates instance payment method', function () {
    $paymentMethod = new PaymentMethod(1);
    expect($paymentMethod)->toBeInstanceOf(PaymentMethod::class);
});

it('validates a valid payment method', function () {
    $paymentMethod = new PaymentMethod(1);
    expect($paymentMethod->value())->toBe(1);
});

it('throws an exception for a payment method other than 1, 2 or 3.', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Type must be 1, 2 or 3.');
    new PaymentMethod(4);
});
