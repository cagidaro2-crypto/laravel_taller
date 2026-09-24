<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ProveedorController;
use App\Http\Controllers\Admin\OrdenTrabajoController;
use App\Http\Controllers\Admin\CotizacionController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\FacturaController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\VehiculoController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Administrador'])->group(function () {

    // RF-05: Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // RF-10 al RF-16: Usuarios / empleados
    Route::resource('usuarios', UsuarioController::class);

    // Servicios
    Route::resource('servicios', ServicioController::class);

    // RF-65 al RF-71: Productos / catálogo
    Route::resource('productos', ProductoController::class);
    Route::patch('productos/{producto}/desactivar', [ProductoController::class, 'desactivar'])->name('productos.desactivar');

    // RF-80 al RF-83: Proveedores
    Route::resource('proveedores', ProveedorController::class);

    // RF-65 al RF-73: Inventario
    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::patch('inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');

    // RF-49 al RF-57: Órdenes de trabajo
    Route::resource('ordenes', OrdenTrabajoController::class)->except(['destroy']);
    Route::post('ordenes/{ordene}/factura', [OrdenTrabajoController::class, 'generarFactura'])->name('ordenes.factura');

    // RF-39 al RF-48: Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class)->except(['destroy']);
    Route::post('cotizaciones/{cotizacione}/factura', [CotizacionController::class, 'convertirFactura'])->name('cotizaciones.factura');
    Route::patch('cotizaciones/{cotizacione}/rechazar', [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');

    // RF-58 al RF-64: Facturas
    Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
    Route::post('facturas/{factura}/pago', [FacturaController::class, 'registrarPago'])->name('facturas.pago');

    // RF-74 al RF-79: Ventas
    Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');

    // RF-84 al RF-88: Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/productividad', [ReporteController::class, 'productividad'])->name('reportes.productividad');
    Route::get('reportes/ingresos', [ReporteController::class, 'ingresos'])->name('reportes.ingresos');
    Route::get('reportes/consumo', [ReporteController::class, 'consumo'])->name('reportes.consumo');
    Route::get('reportes/exportar', [ReporteController::class, 'exportarCSV'])->name('reportes.exportar');

    // RF-21 al RF-23: Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'show']);
});
