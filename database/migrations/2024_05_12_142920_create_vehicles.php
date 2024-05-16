<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {

    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer')->nullable();
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->string('license_plate');
            $table->dateTime('entry_times');
            $table->dateTime('departure_times')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
