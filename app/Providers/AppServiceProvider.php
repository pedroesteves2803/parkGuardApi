<?php

namespace App\Providers;

use App\Repositories\Administration\EloquentEmployeeRepository;
use App\Repositories\Vehicles\ApiConsultVehicleRepository;
use App\Repositories\Vehicles\EloquentVehicleRepository;
use App\Services\SendPendingNotificationService;
use Illuminate\Support\ServiceProvider;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
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
        app()->bind(IConsultVehicleRepository::class, ApiConsultVehicleRepository::class);
        app()->bind(ISendPendingNotificationService::class, SendPendingNotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
