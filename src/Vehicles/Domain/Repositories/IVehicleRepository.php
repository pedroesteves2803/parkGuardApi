<?php

namespace Src\Vehicles\Domain\Repositories;

use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

interface IVehicleRepository
{
    // public function getAll(): ?Collection;

    public function getById(int $id): ?Vehicle;

    public function create(Vehicle $vehicle): Vehicle;

    public function update(Vehicle $vehicle): ?Vehicle;

    // public function delete(int $id): void;

    public function existVehicle(LicensePlate $licensePlate): bool;
}
