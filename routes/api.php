<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\VehicleApiController;
use App\Http\Controllers\Api\WorkOrderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    Route::prefix('work-orders')->group(function () {
        Route::get('/', [WorkOrderApiController::class, 'index']);
        Route::post('/', [WorkOrderApiController::class, 'store']);
        Route::get('/stats', [WorkOrderApiController::class, 'stats']);
        Route::get('/{id}', [WorkOrderApiController::class, 'show']);
        Route::patch('/{order}/status', [WorkOrderApiController::class, 'changeStatus']);
        Route::post('/{order}/spare-parts', [WorkOrderApiController::class, 'addSparePart']);
    });

    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleApiController::class, 'index']);
        Route::post('/', [VehicleApiController::class, 'store']);
        Route::get('plate/{plate}', [VehicleApiController::class, 'showByPlate']);
        Route::get('/client/{clientId}', [VehicleApiController::class, 'getByClient']);
    });
});
