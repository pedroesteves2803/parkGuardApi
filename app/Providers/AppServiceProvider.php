<?php

namespace App\Providers;

use App\Repositories\Administration\EloquentEmployeeRepository;
use App\Repositories\Administration\EloquentPasswordResetRepository;
use App\Repositories\Payments\EloquentPaymentRepository;
use App\Repositories\Vehicles\ApiConsultVehicleRepository;
use App\Repositories\Vehicles\EloquentVehicleRepository;
use App\Services\LoginEmployeeService;
use App\Services\SendPasswordResetTokenService;
use App\Services\SendPendingNotificationService;
use Illuminate\Support\ServiceProvider;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Vehicles\Application\Service\VehicleService;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\Service\IVehicleService;
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
        app()->bind(ILoginEmployeeService::class, LoginEmployeeService::class);
        app()->bind(IPaymentRepository::class, EloquentPaymentRepository::class);
        app()->bind(IPasswordResetRepository::class, EloquentPasswordResetRepository::class);
        app()->bind(ISendPasswordResetTokenService::class, SendPasswordResetTokenService::class);
        app()->bind(IVehicleService::class, VehicleService::class);

        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
