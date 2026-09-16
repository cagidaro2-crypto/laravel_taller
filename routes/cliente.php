<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\VehiculoController;
use App\Http\Controllers\Cliente\CotizacionController;
use App\Http\Controllers\Cliente\HistorialVehiculoController;
use App\Http\Controllers\Cliente\CitaController;

Route::prefix('cliente')->name('cliente.')->middleware(['auth', 'role:Cliente'])->group(function () {

    // RF-05
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // RF-17 al RF-20, RF-25, RF-26: Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('vehiculos/{vehiculo}/foto', [VehiculoController::class, 'subirFoto'])->name('vehiculos.foto');

    // RF-43 al RF-45: Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class)->only(['index', 'show']);
    Route::patch('cotizaciones/{cotizacione}/aprobar', [CotizacionController::class, 'aprobar'])->name('cotizaciones.aprobar');
    Route::patch('cotizaciones/{cotizacione}/rechazar', [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');

    // RF-20: Historial
    Route::resource('historial', HistorialVehiculoController::class)->only(['index', 'show']);

    // RF-29 al RF-33: Citas
    Route::resource('citas', CitaController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
});
