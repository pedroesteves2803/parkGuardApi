<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasswordResetTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'token' => Str::random(5),
            'expiration_date' => now()->addMinutes(40),
        ];
    }
}
