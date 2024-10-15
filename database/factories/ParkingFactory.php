<?php

namespace Database\Factories;

use App\Models\Parking;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParkingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Parking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> 'Estacionamento',
            'responsible_identification' => '50598542809',
            'responsible_name' => 'Pedro',
            'price_per_hour' => 10.0,
            'additional_hour_price' => 2.0,
        ];
    }
}
