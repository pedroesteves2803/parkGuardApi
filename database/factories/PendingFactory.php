<?php

namespace Database\Factories;

use App\Models\Pending;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PendingFactory extends Factory
{
    protected $model = Pending::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'description' => $this->faker->text(),
            'vehicle_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
