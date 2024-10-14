<?php

namespace Src\Administration\Domain\Factory;

use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\PricePerHour;

class ParkingFactory
{
    public function create(
        ?int $id,
        string $name,
        string $responsibleIdentification,
        string $responsibleName,
        float $pricePerHour,
        float $additionalHourPrice,
    ): Parking
    {
        return new Parking(
            $id,
            New Name($name),
            $responsibleIdentification,
            $responsibleName,
            new PricePerHour($pricePerHour),
            new AdditionalHourPrice($additionalHourPrice)
        );
    }
}
