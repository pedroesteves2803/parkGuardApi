<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
Route::get('/employee/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
Route::patch('/employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
Route::get('/vehicle/{vehicle}', [VehicleController::class, 'show'])->name('vehicle.show');
Route::patch('/vehicle/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
Route::post('/vehicle/exit/{vehicle}', [VehicleController::class, 'exit'])->name('vehicle.exit');

Route::post('/payments', [PaymentController::class, 'store'])->name('payment.create');


Route::get('/teste/{id}', function($id){
    $vehicle = Vehicle::where('id', $id)->with('pendings')->first();

    dd($vehicle);
});
