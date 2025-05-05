<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/reservas', function () {
        return Inertia::render('reservas'); // importante: el nombre en minúsculas por convención
    })->name('reservas');

    Route::get('/mesas', function () {
        return Inertia::render('mesas'); // importante: el nombre en minúsculas por convención
    })->name('mesas');

    Route::get('/empleados', function () {
        return Inertia::render('empleados'); // importante: el nombre en minúsculas por convención
    })->name('empleados');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
