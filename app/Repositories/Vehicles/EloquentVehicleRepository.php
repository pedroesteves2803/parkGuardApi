<?php

namespace App\Repositories\Vehicles;

use App\Models\Pending as ModelsPending;
use App\Models\Vehicle as ModelsVehicle;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final class EloquentVehicleRepository implements IVehicleRepository
{
    public function getAll(): ?Collection
    {
        $vehicles = ModelsVehicle::all();

        $vehicles = $vehicles->map(function ($vehicle) {
            return new Vehicle(
                $vehicle->id,
                new Manufacturer($vehicle->manufacturer),
                new Color($vehicle->color),
                new Model($vehicle->model),
                new LicensePlate($vehicle->license_plate),
                new EntryTimes($vehicle->entry_times),
                new DepartureTimes($vehicle->departure_times)
            );
        });

        return $vehicles;
    }

    public function getById(int $id): ?Vehicle
    {
        $vehicle = ModelsVehicle::find($id);

        if (is_null($vehicle)) {
            return null;
        }

        return new Vehicle(
            $vehicle->id,
            new Manufacturer($vehicle->manufacturer),
            new Color($vehicle->color),
            new Model($vehicle->model),
            new LicensePlate($vehicle->license_plate),
            new EntryTimes($vehicle->entry_times),
            new DepartureTimes($vehicle->departure_times)
        );
    }

    public function create(Vehicle $vehicle): Vehicle
    {
        $modelsVehicle = new ModelsVehicle();
        $modelsVehicle->manufacturer = $vehicle->manufacturer->value();
        $modelsVehicle->color = $vehicle->color->value();
        $modelsVehicle->model = $vehicle->model->value();
        $modelsVehicle->license_plate = $vehicle->licensePlate->value();
        $modelsVehicle->entry_times = $vehicle->entryTimes->value();
        $modelsVehicle->save();

        return new Vehicle(
            $modelsVehicle->id,
            new Manufacturer($modelsVehicle->manufacturer),
            new Color($modelsVehicle->color),
            new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes(
                $modelsVehicle->entry_times
            ),
            new DepartureTimes(
                $modelsVehicle->departure_times
            )
        );
    }

    public function update(Vehicle $vehicle): ?Vehicle
    {
        $modelsVehicle = ModelsVehicle::find($vehicle->id);

        if (is_null($vehicle)) {
            return null;
        }

        $modelsVehicle->manufacturer = $vehicle->manufacturer;
        $modelsVehicle->color = $vehicle->color;
        $modelsVehicle->model = $vehicle->model;
        $modelsVehicle->license_plate = $vehicle->licensePlate;
        $modelsVehicle->update();

        return new Vehicle(
            $vehicle->id,
            new Manufacturer($vehicle->manufacturer),
            new Color($vehicle->color),
            new Model($vehicle->model),
            new LicensePlate($vehicle->licensePlate),
            $vehicle->entryTimes,
            $vehicle->departureTimes
        );
    }

    public function existVehicle(LicensePlate $licensePlate): bool
    {
        $vehicle = ModelsVehicle::where(['license_plate' => $licensePlate, 'departure_times' => null])->exists();

        return $vehicle;
    }

    public function exit(LicensePlate $licensePlate): Vehicle
    {
        $departureTimes = new DepartureTimes(
            new DateTime()
        );

        $modelsVehicle = ModelsVehicle::where(['license_plate' => $licensePlate->value(), 'departure_times' => null])->first();
        $modelsVehicle->departure_times = $departureTimes->value();
        $modelsVehicle->update();

        return new Vehicle(
            $modelsVehicle->id,
            new Manufacturer($modelsVehicle->manufacturer),
            new Color($modelsVehicle->color),
            new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes(
                $modelsVehicle->entry_times
            ),
            new DepartureTimes(
                $modelsVehicle->departure_times
            )
        );
    }

    public function addPending(Vehicle $vehicle): Collection
    {
        $vehicle->pendings()->map(function (Pending $pendingItem) use ($vehicle) {
            $modelsPending = new ModelsPending();
            $modelsPending->type = $pendingItem->type->value();
            $modelsPending->description = $pendingItem->description->value();
            $modelsPending->vehicle_id = $vehicle->id();
            $modelsPending->save();
        });

        return $vehicle->pendings();
    }
}
