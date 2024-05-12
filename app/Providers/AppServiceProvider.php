<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Infrastructure\EloquentEmployeeRepository;
use Src\Vehicles\Infrastructure\EloquentVehicleRepository;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(IEmployeeRepository::class, EloquentEmployeeRepository::class);
        app()->bind(IVehicleRepository::class, EloquentVehicleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
