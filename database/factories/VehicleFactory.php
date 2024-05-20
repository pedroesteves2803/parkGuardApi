<?php

namespace Database\Factories;

use App\Models\Vehicle;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'manufacturer' => 'Honda',
            'color' => 'Azul',
            'model' => 'Civic',
            'license_plate' => 'DGF-1798',
            'entry_times' => new DateTime(),
            'departure_times' => null,
        ];
    }
}
