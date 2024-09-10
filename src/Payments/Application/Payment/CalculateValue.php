<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\CalculateValueOutputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

class CalculateValue
{
    private const VALUE_HOUR = 2000;

    private const MORE_THAN_AN_HOUR = 1000;

    public function __construct(
        private readonly Notification $notification,
    )
    {}

    public function execute(Vehicle $vehicle): CalculateValueOutputDto
    {
        if (is_null($vehicle->departureTimes())) {
            $this->notification->addError([
                'context' => 'calculate_value',
                'message' => 'Horário de partida não está definido!',
            ]);

            return new CalculateValueOutputDto(null, $this->notification);
        }

        $entryTimes = $vehicle->entryTimes()->value();
        $departureTimes = $vehicle->departureTimes()->value();

        $interval = $entryTimes->diff($departureTimes);

        $hours = $interval->h;
        $minutes = $interval->i;

        if ($minutes > 0) {
            $hours++;
        }

        if ($hours <= 1) {
            $totalToPay = self::VALUE_HOUR;
        } else {
            $totalToPay = self::VALUE_HOUR + ($hours - 1) * self::MORE_THAN_AN_HOUR;
        }

        return new CalculateValueOutputDto($totalToPay, $this->notification);
    }

}
