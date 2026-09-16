<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tecnico\DashboardController;
use App\Http\Controllers\Tecnico\CitaController;
use App\Http\Controllers\Tecnico\OrdenTrabajoController;
use App\Http\Controllers\Tecnico\VehiculoController;
use App\Http\Controllers\Tecnico\HistorialVehiculoController;
use App\Http\Controllers\Tecnico\VentaController;

Route::prefix('tecnico')->name('tecnico.')->middleware(['auth', 'role:Técnico,Empleado'])->group(function () {

    // RF-05
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // RF-36: Citas asignadas
    Route::resource('citas', CitaController::class)->only(['index', 'show']);

    // RF-53, RF-54: Órdenes
    Route::resource('ordenes', OrdenTrabajoController::class)->only(['index', 'show']);
    Route::patch('ordenes/{ordene}/estado', [OrdenTrabajoController::class, 'actualizarEstado'])->name('ordenes.estado');

    // RF-21 al RF-23, RF-25, RF-26: Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'show']);
    Route::patch('vehiculos/{vehiculo}/estado', [VehiculoController::class, 'actualizarEstado'])->name('vehiculos.estado');
    Route::post('vehiculos/{vehiculo}/foto', [VehiculoController::class, 'subirFoto'])->name('vehiculos.foto');

    // RF-20: Historial
    Route::resource('historial', HistorialVehiculoController::class)->only(['index', 'show']);

    // RF-74: Ventas (técnico registra)
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
});
