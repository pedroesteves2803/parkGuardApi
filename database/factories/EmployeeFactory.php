<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelsEmployee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'type' => $this->faker->randomElement([0, 1]), // Usando enum [0, 1] para representar os tipos
        ];
    }
}
