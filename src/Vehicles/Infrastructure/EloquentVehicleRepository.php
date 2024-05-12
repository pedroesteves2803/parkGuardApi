<?php

namespace Src\Administration\Infrastructure;

use App\Models\Employee as ModelsEmployee;
use App\Models\Vehicle as ModelsVehicle;
use DateTime;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
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
            new EntryTimes(new DateTime($vehicle->entry_times)),
            new DepartureTimes(new DateTime($vehicle->departure_times))
        );
    }

    public function create(Vehicle $vehicle): Vehicle
    {
        $modelsVehicle = new ModelsVehicle();
        $modelsVehicle->manufacturer = $vehicle->manufacturer->value();
        $modelsVehicle->color = $vehicle->color->value();
        $modelsVehicle->model =$vehicle->model->value();
        $modelsVehicle->license_plate = $vehicle->entryTimes->value();
        $modelsVehicle->departure_times = $vehicle->departureTimes->value();
        $modelsVehicle->save();

        return new Vehicle(
            $modelsVehicle->id,
            new Manufacturer($modelsVehicle->manufacturer),
            new Color($modelsVehicle->color),
            new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes(new DateTime($modelsVehicle->entry_times)),
            new DepartureTimes(new DateTime($modelsVehicle->departure_times))
        );
    }

    public function existVehicle(string $licensePlate): bool {

        $vehicle = ModelsVehicle::where(['license_plate' => $licensePlate, 'departure_times' => null])->exists();
        return $vehicle;
    }
}
