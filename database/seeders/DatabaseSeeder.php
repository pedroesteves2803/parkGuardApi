<?php

namespace Database\Seeders;

use App\Models\Employee;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Employee::factory()->create([
            'name' => 'Pedro',
            'email' => 'pedrocab498@gmail.com',
            'password' => Hash::make('Pedro156_'),
            'type' => 1,
        ]);
    }
}
