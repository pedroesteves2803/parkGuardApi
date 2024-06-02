<?php

namespace Src\Payments\Domain\Entities;

use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\Entities\Vehicle;

class Payment extends Entity implements IAggregator
{
    public function __construct(
        readonly ?int $id,
        readonly Value $value,
        readonly DateTime $dateTime,
        readonly PaymentMethod $paymentMethod,
        readonly Vehicle $vehicle,
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

    public function dateTime(): DateTime
    {
        return $this->dateTime;
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
