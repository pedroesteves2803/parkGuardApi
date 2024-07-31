<?php

namespace App\Repositories\Vehicles;

use App\Models\Pending as ModelsPending;
use App\Models\Vehicle as ModelsVehicle;
use DateTime;
use Illuminate\Support\Collection;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;

final class EloquentVehicleRepository implements IVehicleRepository
{
    public function getAll(): ?Collection
    {
        $vehicles = ModelsVehicle::orderBy('id', 'desc')->get();

        $vehicles = $vehicles->map(function ($vehicle) {
            return new Vehicle(
                $vehicle->id,
                is_null($vehicle->manufacturer) ? null : new Manufacturer($vehicle->manufacturer),
                is_null($vehicle->color) ? null : new Color($vehicle->color),
                is_null($vehicle->model) ? null : new Model($vehicle->model),
                new LicensePlate($vehicle->license_plate),
                new EntryTimes($vehicle->entry_times),
                new DepartureTimes($vehicle->departure_times)
            );
        });

        return $vehicles;
    }

    public function getById(int $id): ?Vehicle
    {
        $modelsVehicle = ModelsVehicle::find($id);

        if (is_null($modelsVehicle)) {
            return null;
        }

        return new Vehicle(
            $modelsVehicle->id,
            is_null($modelsVehicle->manufacturer) ? null : new Manufacturer($modelsVehicle->manufacturer),
            is_null($modelsVehicle->color) ? null : new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? null : new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes($modelsVehicle->entry_times),
            is_null($modelsVehicle->departure_times) ? null : new DepartureTimes($modelsVehicle->departure_times)
        );
    }

    public function create(Vehicle $vehicleEntity): Vehicle
    {
        $modelsVehicle = new ModelsVehicle();
        $modelsVehicle->manufacturer = is_null($vehicleEntity->manufacturer()) ? null : $vehicleEntity->manufacturer()->value();
        $modelsVehicle->color = is_null($vehicleEntity->color()) ? null : $vehicleEntity->color()->value();
        $modelsVehicle->model = is_null($vehicleEntity->model()) ? null : $vehicleEntity->model()->value();
        $modelsVehicle->license_plate = $vehicleEntity->licensePlate()->value();
        $modelsVehicle->entry_times = $vehicleEntity->entryTimes()->value();
        $modelsVehicle->save();

        $vehicle = new Vehicle(
            $modelsVehicle->id,
            is_null($modelsVehicle->manufacturer) ? $modelsVehicle->manufacturer : new Manufacturer($modelsVehicle->manufacturer),
            is_null($modelsVehicle->color) ? $modelsVehicle->color : new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? $modelsVehicle->model : new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes(
                $modelsVehicle->entry_times
            ),
            new DepartureTimes(
                $modelsVehicle->departure_times
            )
        );

        $vehicleEntity->pendings()->map(function (Pending $pendingItem) use ($modelsVehicle, $vehicle) {
            if (! is_null($pendingItem->description)) {

                $modelsPending = new ModelsPending();
                $modelsPending->type = $pendingItem->type->value();
                $modelsPending->description = $pendingItem->description->value();
                $modelsPending->vehicle_id = $modelsVehicle->id;
                $modelsPending->save();

                $vehicle->addPending(
                    new Pending(
                        $modelsPending->id,
                        new Type($modelsPending->type),
                        new Description($modelsPending->description)
                    )
                );
            }
        });

        return $vehicle;
    }

    public function update(Vehicle $vehicle): ?Vehicle
    {
        $modelsVehicle = ModelsVehicle::find($vehicle->id());

        if (is_null($vehicle)) {
            return null;
        }

        if (! is_null($vehicle->manufacturer())) {
            $modelsVehicle->manufacturer = $vehicle->manufacturer();
        }

        if (! is_null($vehicle->color())) {
            $modelsVehicle->color = $vehicle->color();
        }

        if (! is_null($vehicle->model())) {
            $modelsVehicle->model = $vehicle->model();
        }

        if (! is_null($vehicle->licensePlate())) {
            $modelsVehicle->license_plate = $vehicle->licensePlate();
        }

        $modelsVehicle->update();

        return new Vehicle(
            $vehicle->id(),
            is_null($vehicle->manufacturer()) ? null : new Manufacturer($vehicle->manufacturer()),
            is_null($vehicle->color()) ? null : new Color($vehicle->color()),
            is_null($vehicle->model()) ? null : new Model($vehicle->model()),
            new LicensePlate($vehicle->licensePlate()),
            $vehicle->entryTimes(),
            $vehicle->departureTimes()
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
            is_null($modelsVehicle->manufacturer) ? $modelsVehicle->manufacturer : new Manufacturer($modelsVehicle->manufacturer),
            is_null($modelsVehicle->color) ? $modelsVehicle->color : new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? $modelsVehicle->model : new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes(
                $modelsVehicle->entry_times
            ),
            new DepartureTimes(
                $modelsVehicle->departure_times
            )
        );
    }
}
