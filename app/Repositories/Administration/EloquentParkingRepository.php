<?php

namespace App\Repositories\Administration;

use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Repositories\IParkingRepository;

class EloquentParkingRepository implements IParkingRepository
{

    public function getAll(): ?Collection
    {

    }

    public function getById(int $id): ?Parking
    {
        // TODO: Implement getById() method.
    }

    public function create(Parking $parking): Parking
    {
        // TODO: Implement create() method.
    }

    public function update(Parking $parking): ?Parking
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function exists(string $identification): bool
    {
        // TODO: Implement exists() method.
    }
}
