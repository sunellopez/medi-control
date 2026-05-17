<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\UserController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
});


Route::middleware('auth:api')->group(function () {
    Route::get('doctors', [DoctorController::class, 'index']);

    Route::apiResource('patients', PatientController::class);

    Route::apiResource('appointments', AppointmentController::class);

    Route::apiResource('medical-records', MedicalRecordController::class);

    Route::apiResource('inventory/medications', InventoryController::class);
    Route::post('inventory/movements', [InventoryController::class, 'storeMovement']);

    Route::get('reports/dashboard', [ReportController::class, 'dashboard']);
    Route::get('reports/dashboard-stats', [ReportController::class, 'dashboardStats']);

    Route::get('roles', [UserController::class, 'getRoles']);
    Route::apiResource('users', UserController::class);
});
