<?php
namespace Src\Payments\Domain\Entities;

use RuntimeException;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\Entities\Vehicle;

class Payment extends Entity implements IAggregator
{
    private const VALUE_HOUR = 2000;
    private const MORE_THAN_AN_HOUR = 1000;

    public function __construct(
        readonly private ?int $id,
        private Value $value,
        readonly private RegistrationTime $registrationTime,
        readonly private PaymentMethod $paymentMethod,
        readonly private bool $paid,
        readonly private Vehicle $vehicle,
    ) {
    }

    public function id(): ?int
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

    public function calculateTotalToPay(): void
    {
        if (is_null($this->vehicle->departureTimes())) {
            throw new RuntimeException('Horário de partida não está definido!');
        }

        $entryTimes = $this->vehicle->entryTimes()->value();
        $departureTimes = $this->vehicle->departureTimes()->value();
        $interval = $entryTimes->diff($departureTimes);

        $hours = $interval->h;
        $minutes = $interval->i;
        if ($minutes > 0) {
            $hours++;
        }

        $totalToPay = ($hours <= 1)
            ? self::VALUE_HOUR
            : self::VALUE_HOUR + ($hours - 1) * self::MORE_THAN_AN_HOUR;

        $this->value = new Value($totalToPay);
    }
}
