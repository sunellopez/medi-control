<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware('auth:api')->group(function () {
    // Patients CRUD
    Route::apiResource('patients', PatientController::class);
});
