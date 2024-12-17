<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MesaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ReservaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::patch('/{user}/role', [UserController::class, 'updateRole']);
        Route::patch('/{user}/status', [UserController::class, 'updateStatus']);
        Route::patch('/password', [UserController::class, 'updatePassword']);
        Route::patch('/{user}/password', [UserController::class, 'adminUpdatePassword']);
    });

    // Mesa Routes
    Route::get('/mesas/available', [MesaController::class, 'available']);
    Route::apiResource('/mesas', MesaController::class);
    //RESERVAS
    Route::prefix('v1')->group(function () {
        // Rutas protegidas que requieren autenticación
        Route::middleware(['auth:sanctum'])->group(function () {
            // Rutas de reservas
            Route::prefix('reservas')->group(function () {
                Route::get('/', [ReservaController::class, 'index']);
                Route::post('/', [ReservaController::class, 'store']);
                Route::get('/{reserva}', [ReservaController::class, 'show']);
                Route::put('/{reserva}', [ReservaController::class, 'update']);
                Route::delete('/{reserva}', [ReservaController::class, 'cancel']);
                
                // Rutas que requieren rol de administrador
                Route::middleware(['role:admin'])->group(function () {
                    Route::patch('/{reserva}/confirm', [ReservaController::class, 'confirm']);
                    Route::patch('/{reserva}/complete', [ReservaController::class, 'complete']);
                });
            });
        });
    });
});
