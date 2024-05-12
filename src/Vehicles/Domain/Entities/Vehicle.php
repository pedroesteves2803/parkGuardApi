<?php

namespace Src\Vehicles\Domain\Entities;

use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

class Vehicle extends Entity implements IAggregator
{
    public function __construct(
        readonly ?int $id,
        readonly ?Manufacturer $manufacturer,
        readonly ?Color $color,
        readonly ?Model $model,
        readonly LicensePlate $licensePlate,
        readonly EntryTimes $entryTimes,
        readonly ?DepartureTimes $departureTimes,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function manufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function color(): Color
    {
        return $this->color;
    }

    public function model(): Model
    {
        return $this->model;
    }

    public function licensePlate(): LicensePlate
    {
        return $this->licensePlate;
    }

    public function entryTimes(): EntryTimes
    {
        return $this->entryTimes;
    }

    public function departureTimes(): DepartureTimes
    {
        return $this->departureTimes;
    }
}
