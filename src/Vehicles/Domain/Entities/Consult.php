<?php

namespace Src\Vehicles\Domain\Entities;

use Illuminate\Support\Collection;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

class Consult extends Entity implements IAggregator
{
    private $pendencies;

    public function __construct(
        readonly ?Manufacturer $manufacturer,
        readonly ?Color $color,
        readonly ?Model $model,
        readonly ?LicensePlate $licensePlate,
    ) {
        $this->pendencies = new Collection();

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

    public function pendencies(): Collection
    {
        return $this->pendencies;
    }

    public function addPending(Pending $pending): void
    {
        $this->pendencies->push($pending);
    }
}
