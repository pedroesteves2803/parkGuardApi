<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.name');
