<?php

namespace Src\Vehicles\Infrastructure;

use App\Models\Vehicle as ModelsVehicle;
use Illuminate\Support\Collection;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\Entities\Vehicle;

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
                new EntryTimes(new \DateTime($vehicle->entry_times)),
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
            new EntryTimes(new \DateTime($vehicle->entry_times)),
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
                new \DateTime(
                    $modelsVehicle->entry_times->format('Y-m-d H:i:s')
                )
            ),
            $modelsVehicle->departure_times
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
        $modelsVehicle = ModelsVehicle::where(['license_plate' => $licensePlate, 'departure_times' => null]);
        $modelsVehicle->departure_times = new \DateTime();
        $modelsVehicle->update();

        return new Vehicle(
            $modelsVehicle->id,
            new Manufacturer($modelsVehicle->manufacturer),
            new Color($modelsVehicle->color),
            new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->licensePlate),
            $modelsVehicle->entryTimes,
            $modelsVehicle->departureTimes
        );
    }
}
