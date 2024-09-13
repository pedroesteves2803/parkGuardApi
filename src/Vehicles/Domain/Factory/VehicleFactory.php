<?php

namespace Src\Vehicles\Domain\Factory;

use DateTime;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

class VehicleFactory
{
    public function create(
        ?int $id,
        ?string $manufacturer,
        ?string $color,
        ?string $model,
        string $licensePlate,
        DateTime $entryTimes,
        ?DateTime $departureTimes
    ): Vehicle
    {
        return new Vehicle(
            $id,
            new Manufacturer($manufacturer),
            new Color($color),
            new Model($model),
            new LicensePlate($licensePlate),
            new EntryTimes($entryTimes),
            New DepartureTimes($departureTimes)
        );
    }

    public function createWithPendings(
        ?int $id,
        ?string $manufacturer,
        ?string $color,
        ?string $model,
        ?string $licensePlate,
        DateTime $entryTimes,
        ?DateTime $departureTimes,
        ?array $pendings
    ): Vehicle
    {
        $vehicle = new Vehicle(
            $id,
            empty($manufacturer) ? null : new Manufacturer($manufacturer),
            empty($color) ? null : new Color($color),
            empty($model) ? null : new Model($model),
            empty($licensePlate) ? null : new LicensePlate($licensePlate),
            new EntryTimes($entryTimes),
            $departureTimes === null ? null : New DepartureTimes($departureTimes)
        );

        foreach ($pendings as $pending) {
            $vehicle->addPending($pending);
        }

        return $vehicle;
    }
}
