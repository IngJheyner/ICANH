<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas API para tu aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas serán asignadas al
| grupo de middleware "api".
|
*/

// Rutas públicas de la API (sin autenticación)

// Rutas de Marcas de Vehículos
Route::prefix('marcas-vehiculo')->group(function () {
    Route::get('/', [VehicleBrandController::class, 'index']);
    Route::post('/', [VehicleBrandController::class, 'store']);
    Route::get('/{id}', [VehicleBrandController::class, 'show']);
    Route::put('/{id}', [VehicleBrandController::class, 'update']);
    Route::delete('/{id}', [VehicleBrandController::class, 'destroy']);
});

// Rutas de Personas
Route::prefix('personas')->group(function () {
    Route::get('/', [PersonController::class, 'index']);
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{id}', [PersonController::class, 'show']);
    Route::put('/{id}', [PersonController::class, 'update']);
    Route::delete('/{id}', [PersonController::class, 'destroy']);
    // Endpoint especial: Obtener vehículos de una persona
    Route::get('/{id}/vehiculos', [PersonController::class, 'vehicles']);
});

// Rutas de Vehículos
Route::prefix('vehiculos')->group(function () {
    Route::get('/', [VehicleController::class, 'index']);
    Route::post('/', [VehicleController::class, 'store']);
    Route::get('/{id}', [VehicleController::class, 'show']);
    Route::put('/{id}', [VehicleController::class, 'update']);
    Route::delete('/{id}', [VehicleController::class, 'destroy']);
    // Endpoint especial: Asignar propietario a un vehículo
    Route::post('/{id}/propietarios', [VehicleController::class, 'addOwner']);
});
