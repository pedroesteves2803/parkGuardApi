<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Catalogue\Infrastructure\EloquentEmployeeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(IEmployeeRepository::class, EloquentEmployeeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
