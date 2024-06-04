<?php

namespace Src\Payments\Domain\Entities;

use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\Entities\Vehicle;

class Payment extends Entity implements IAggregator
{
    public function __construct(
        readonly private ?int $id,
        readonly private Value $value,
        readonly private RegistrationTime $registrationTime,
        readonly private PaymentMethod $paymentMethod,
        readonly private bool $paid,
        readonly private Vehicle $vehicle,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function value(): Value
    {
        return $this->value;
    }

    public function registrationTime(): RegistrationTime
    {
        return $this->registrationTime;
    }

    public function paid(): bool
    {
        return $this->paid;
    }

    public function vehicle(): Vehicle
    {
        return $this->vehicle;
    }

    public function paymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }
}
