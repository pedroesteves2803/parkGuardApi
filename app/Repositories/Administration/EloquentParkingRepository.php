<?php

namespace App\Repositories\Administration;

use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use App\Models\Parking as ModelsParking;

final readonly class EloquentParkingRepository implements IParkingRepository
{
    public function __construct(
        private ParkingFactory $parkingFactory
    )
    {}
    public function getAll(): ?Collection
    {
        throw new \RuntimeException('Não implementado!');
    }

    public function getById(int $id): ?Parking
    {
        $parking = ModelsParking::find($id);

        if (is_null($parking)) {
            return null;
        }

        return $this->parkingFactory->create(
            $parking->id,
            $parking->name,
            $parking->responsible_identification,
            $parking->responsible_name,
            $parking->price_per_hour,
            $parking->additional_hour_price,
        );
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
        $modelsParking = ModelsParking::find($parking->id());
        $modelsParking->name = $parking->name()->value();
        $modelsParking->responsible_identification = $parking->responsibleIdentification();
        $modelsParking->responsible_name = $parking->responsibleName()->value();
        $modelsParking->price_per_hour = $parking->pricePerHour()->value();
        $modelsParking->additional_hour_price = $parking->additionalHourPrice()->value();
        $modelsParking->update();

        if (is_null($modelsParking)) {
            return null;
        }

        return $this->parkingFactory->create(
            $modelsParking->id,
            $modelsParking->name,
            $modelsParking->responsible_identification,
            $modelsParking->responsible_name,
            $modelsParking->price_per_hour,
            $modelsParking->additional_hour_price,
        );
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
