<?php

namespace Database\Seeders;

use App\Models\Employee;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Parking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $parking = Parking::factory()->create([
            'responsible_identification' => '50598542809',
            'responsible_name' => 'Estacionamento',
            'price_per_hour' => 10.0,
            'additional_hour_price' => 2.0,
        ]);

        Employee::factory()->create([
            'name' => 'Pedro',
            'email' => 'pedrocab498@gmail.com',
            'password' => Hash::make('Pedro156_'),
            'parking_id' => $parking->id,
            'type' => 1,
        ]);
    }
}
