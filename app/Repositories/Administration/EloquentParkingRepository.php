<?php

namespace App\Repositories\Administration;

use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use App\Models\Parking as ModelsParking;

class EloquentParkingRepository implements IParkingRepository
{
    public function __construct(
        private readonly ParkingFactory $parkingFactory
    )
    {}
    public function getAll(): ?Collection
    {
        throw new \RuntimeException('Não implementado!');
    }

    public function getById(int $id): ?Parking
    {
        throw new \RuntimeException('Não implementado!');
    }

    public function create(Parking $parking): Parking
    {
        $modelsParking = new ModelsParking();
        $modelsParking->name = $parking->name()->value();
        $modelsParking->responsible_identification = $parking->responsibleIdentification();
        $modelsParking->responsible_name = $parking->responsibleName()->value();
        $modelsParking->price_per_hour = $parking->pricePerHour()->value();
        $modelsParking->additional_hour_price = $parking->additionalHourPrice()->value();
        $modelsParking->save();

        return $this->parkingFactory->create(
            $modelsParking->id,
            $modelsParking->name,
            $modelsParking->responsible_identification,
            $modelsParking->responsible_name,
            $modelsParking->price_per_hour,
            $modelsParking->additional_hour_price,
        );
    }

    public function update(Parking $parking): ?Parking
    {
        throw new \RuntimeException('Não implementado!');
    }

    public function delete(int $id): void
    {
        throw new \RuntimeException('Não implementado!');
    }

    public function exists(string $identification): bool
    {
        return ModelsParking::where('responsible_identification', $identification)->exists();
    }
}
