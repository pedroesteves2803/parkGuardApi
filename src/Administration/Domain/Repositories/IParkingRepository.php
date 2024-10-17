<?php

namespace Src\Administration\Domain\Repositories;

use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Parking;

interface IParkingRepository
{
    public function getAll(): ?Collection;

    public function getById(int $id): ?Parking;

    public function create(Parking $parking): Parking;

    public function update(Parking $parking): ?Parking;

    public function delete(int $id): void;

    public function exists(string $identification): bool;
}
