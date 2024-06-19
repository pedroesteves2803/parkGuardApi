<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/employee/login', [EmployeeController::class, 'login'])->name('employee.login');
Route::post('/employee/logout', [EmployeeController::class, 'logout'])->name('employee.logout');

Route::middleware(['jwt.auth'])->group(function () {

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
    Route::put('/employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::get('/vehicle/{vehicle}', [VehicleController::class, 'show'])->name('vehicle.show');
    Route::put('/vehicle/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
    Route::post('/vehicle/exit', [VehicleController::class, 'exit'])->name('vehicle.exit');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.create');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{payment}/finalize', [PaymentController::class, 'finalize'])->name('payment.finalize');
});
