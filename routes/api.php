<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\TableController;

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas de empleados
    Route::get('/empleado', [EmployeeController::class, 'index']);
    Route::post('/empleado', [EmployeeController::class, 'store']);
    Route::get('/empleado/{id}', [EmployeeController::class, 'show']);
    Route::delete('/empleado/{id}', [EmployeeController::class, 'destroy']);
    Route::put('/empleado/{id}', [EmployeeController::class, 'update']);
    Route::patch('/empleado/{id}', [EmployeeController::class, 'updatePartial']);

    // Rutas de mesas
    Route::get('/mesa', [TableController::class, 'index']);
    Route::post('/mesa', [TableController::class, 'store']);
    Route::get('/mesa/{id}', [TableController::class, 'show']);
    Route::delete('/mesa/{id}', [TableController::class, 'destroy']);
    Route::put('/mesa/{id}', [TableController::class, 'update']);
    Route::patch('/mesa/{id}', [TableController::class, 'updatePartial']);

    // Rutas de reservas
    Route::get('/reserva', [ReservationController::class, 'index']);
    Route::post('/reserva', [ReservationController::class, 'store']);
    Route::get('/reserva/{id}', [ReservationController::class, 'show']);
    Route::put('/reserva/{id}', [ReservationController::class, 'update']);
    Route::patch('/reserva/{id}', [ReservationController::class, 'updatePartial']);
    Route::delete('/reserva/{id}', [ReservationController::class, 'destroy']);

    //Reservar
    Route::post('/reservar',[ReservationController::class, 'reservar']);  
    Route::post('/respuesta', [ReservationController::class, 'respuesta']);
    Route::get('/respuesta/verdadero', [ReservationController::class, 'respuestaVerdadero']);
    Route::post('/respuesta/falso', [ReservationController::class, 'respuestaFalso']);
});
