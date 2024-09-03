<?php

namespace Src\Vehicles\Domain\Entities;

use Illuminate\Support\Collection;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

class Vehicle extends Entity implements IAggregator
{
    private Collection $pending;

    public function __construct(
        readonly private ?int            $id,
        readonly private ?Manufacturer   $manufacturer,
        readonly private ?Color          $color,
        readonly private ?Model          $model,
        readonly private LicensePlate    $licensePlate,
        readonly private EntryTimes      $entryTimes,
        readonly private ?DepartureTimes $departureTimes,
    )
    {
        $this->pending = new Collection();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function manufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function color(): ?Color
    {
        return $this->color;
    }

    public function model(): ?Model
    {
        return $this->model;
    }

    public function licensePlate(): ?LicensePlate
    {
        return $this->licensePlate;
    }

    public function entryTimes(): ?EntryTimes
    {
        return $this->entryTimes;
    }

    public function departureTimes(): ?DepartureTimes
    {
        return $this->departureTimes;
    }

    public function pending(): Collection
    {
        return $this->pending;
    }

    public function addPending(Pending $pending): void
    {
        $this->pending->push($pending);
    }

    public function hasRestrictions(): bool
    {
        return $this->pending->contains(function (Pending $pending) {
            return !is_null($pending->description) && $pending->description->value() !== 'SEM RESTRICAO';
        });
    }
}
