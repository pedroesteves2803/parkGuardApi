<?php

namespace Src\Administration\Domain\Entities;

use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Shared\Domain\ValueObjects\Color;
use Src\Shared\Domain\ValueObjects\DepartureTimes;
use Src\Shared\Domain\ValueObjects\EntryTimes;
use Src\Shared\Domain\ValueObjects\LicensePlate;
use Src\Shared\Domain\ValueObjects\Manufacturer;
use Src\Shared\Domain\ValueObjects\Model;

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

    public function color(): string
    {
        return $this->color;
    }

    public function model(): Model
    {
        return $this->model;
    }

    public function licensePlate(): string
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

    public function __toString(): string
    {
        return "Veiculo:";
    }
}
