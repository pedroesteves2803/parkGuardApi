<?php

namespace Src\Payments\Domain\Factory;

use DateTime;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

class PaymentFactory
{
    public function create(
        ?int $id,
        int $valueAmount,
        DateTime $registrationTime,
        string $paymentMethod,
        bool $paid,
        Vehicle $vehicle
    ): Payment {
        return new Payment(
            $id,
            new Value($valueAmount),
             new RegistrationTime($registrationTime),
            new PaymentMethod($paymentMethod),
            $paid,
            $vehicle,
            new Notification()
        );
    }

    public function createWithCalculation(
        ?int $id,
        DateTime $registrationTime,
        string $paymentMethod,
        bool $paid,
        Vehicle $vehicle
    ): Payment {
        $payment = new Payment(
            $id,
            new Value(0),
            new RegistrationTime($registrationTime),
            new PaymentMethod($paymentMethod),
            $paid,
            $vehicle,
            new Notification()
        );

        $payment->calculateTotalToPay();

        return $payment;
    }
}
