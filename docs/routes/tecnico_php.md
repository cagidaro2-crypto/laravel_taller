# 📄 routes/tecnico.php - Documentación Línea por Línea

## Resumen General
Define todas las rutas del panel técnico. Protegidas por autenticación y rol ('Técnico' o 'Empleado'). Técnico es quien ejecuta el trabajo: actualiza estados de órdenes, registra consumo de materiales, actualiza estado de vehículos, crea facturas.

**Archivo**: `routes/tecnico.php`  
**Responsabilidad**: Endpoints para panel técnico  
**Middleware**: `['auth', 'role:Técnico,Empleado']` (múltiples roles permitidos)  

**Dependencias:**
- `Laravel\Routing\Router`
- `App\Http\Controllers\Tecnico\*` (controladores técnico)

**Si se borra:**
- Panel técnico no funciona
- `/tecnico/*` → 404

---

## Línea por Línea

### Línea 1-10: Imports
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tecnico\DashboardController;
use App\Http\Controllers\Tecnico\CitaController;
use App\Http\Controllers\Tecnico\OrdenTrabajoController;
use App\Http\Controllers\Tecnico\ConsumoMaterialController;
use App\Http\Controllers\Tecnico\VehiculoController;
use App\Http\Controllers\Tecnico\HistorialVehiculoController;
use App\Http\Controllers\Tecnico\VentaController;
use App\Http\Controllers\Tecnico\FacturaController;
```

**Propósito**: Apertura PHP e importación de controladores

**Controladores técnico:**
- `DashboardController` → Panel principal
- `CitaController` → Ver citas asignadas
- `OrdenTrabajoController` → Gestionar órdenes (actualizar estado, etc.)
- `ConsumoMaterialController` → Registrar consumo de materiales
- `VehiculoController` → Ver vehículos y actualizarlos
- `HistorialVehiculoController` → Ver historial de reparaciones
- `VentaController` → Registrar ventas (nuevo)
- `FacturaController` → Crear facturas

**Dependencias:** Todos deben estar en `app/Http/Controllers/Tecnico/`

---

### Línea 12: Grupo de Rutas
```php
Route::prefix('tecnico')->name('tecnico.')->middleware(['auth', 'role:Técnico,Empleado'])->group(function () {
```

**Propósito**: Prefijo 'tecnico', nombre 'tecnico.', y middlewares de protección

**Desglose:**

#### `Route::prefix('tecnico')`
- Prefijo `/tecnico` en todas las rutas
- URL: `/tecnico/dashboard`, `/tecnico/ordenes`, etc.

#### `.name('tecnico.')`
- Prefijo 'tecnico.' en nombres de ruta
- `route('tecnico.dashboard')` genera `/tecnico/dashboard`

#### `.middleware(['auth', 'role:Técnico,Empleado'])`
- **`auth`** → Usuario debe estar autenticado
- **`role:Técnico,Empleado`** → Rol puede ser "Técnico" O "Empleado"
  - Sintaxis: múltiples roles separados por coma

**Diferencia vs Admin/Cliente:**
```php
// Admin (solo 1 rol):
middleware(['auth', 'role:Administrador'])

// Técnico (múltiples roles):
middleware(['auth', 'role:Técnico,Empleado'])

// Permite: Usuario con rol Técnico O Empleado
```

**Por qué múltiples roles:**
- "Técnico" → Responsable de taller
- "Empleado" → Asistente del técnico
- Ambos pueden ejecutar trabajo similar

---

### Línea 14-15: Dashboard
```php
    // RF-05
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

**Propósito**: Panel principal del técnico

**Ruta**: `GET /tecnico/dashboard`

**Qué muestra:**
- Órdenes asignadas para hoy
- Citas programadas
- Inventario bajo stock
- Últimas ventas registradas

---

### Línea 17-18: Citas
```php
    // RF-36: Citas asignadas
    Route::resource('citas', CitaController::class)->only(['index', 'show']);
```

**Propósito**: Ver citas asignadas al técnico

**Rutas:**
```
GET /tecnico/citas     → citas.index   (listar citas)
GET /tecnico/citas/5   → citas.show    (detalle cita)
```

**Por qué solo lectura:**
- Técnico NO crea citas (las crea cliente o admin)
- Técnico solo ve citas asignadas para ejecutarlas

---

### Línea 20-24: Órdenes de Trabajo
```php
    // RF-53, RF-54: Órdenes
    Route::resource('ordenes', OrdenTrabajoController::class)->only(['index', 'show']);
    Route::patch('ordenes/{ordene}/estado', [OrdenTrabajoController::class, 'actualizarEstado'])->name('ordenes.estado');
    Route::patch('ordenes/{ordene}/estado-vehiculo', [OrdenTrabajoController::class, 'actualizarEstadoVehiculo'])->name('ordenes.estado-vehiculo');
    Route::post('ordenes/{ordene}/factura', [OrdenTrabajoController::class, 'generarFactura'])->name('ordenes.factura');
```

**Propósito**: Gestión de órdenes de trabajo (core del sistema técnico)

**Línea 21 desglose - Resource:**

#### `.only(['index', 'show'])`
```
GET /tecnico/ordenes      → ordenes.index  (mis órdenes)
GET /tecnico/ordenes/5    → ordenes.show   (detalle orden)
```

**Línea 22 desglose - Actualizar Estado Orden:**

#### `PATCH /ordenes/{ordene}/estado`
```
Route::patch('ordenes/{ordene}/estado', ...)
```
- **URL**: `/tecnico/ordenes/5/estado`
- **Método**: PATCH (actualización parcial)
- **Qué hace**: Cambia estado de orden ("En Progreso" → "Terminado", etc.)
- **Nombre**: `ordenes.estado`

**Cómo se usa:**
```javascript
// JavaScript en vista:
fetch(`/tecnico/ordenes/5/estado`, {
    method: 'PATCH',
    body: JSON.stringify({ estado: 'Terminado' })
})
```

**Línea 23 desglose - Actualizar Estado Vehículo:**

#### `PATCH /ordenes/{ordene}/estado-vehiculo`
- **URL**: `/tecnico/ordenes/5/estado-vehiculo`
- **Qué hace**: Actualiza estado del vehículo DENTRO de la orden
  - Ej: "Recibido" → "En Reparación" → "Listo" → "Entregado"
- **Diferencia**: Orden tiene estado general, vehículo tiene estado específico
- **Nombre**: `ordenes.estado-vehiculo`

**Línea 24 desglose - Generar Factura:**

#### `POST /ordenes/{ordene}/factura`
- **URL**: `/tecnico/ordenes/5/factura`
- **Método**: POST (crea recurso)
- **Qué hace**: Convierte orden completada en factura
- **Nombre**: `ordenes.factura`

**Flujo típico:**
```
1. Técnico ve orden "Reparar auto Juan"
2. Actualiza: estado → "Terminado"
3. Actualiza: estado_vehículo → "Entregado"
4. Click "Generar Factura"
5. POST /ordenes/5/factura
6. Sistema crea Factura automáticamente
7. Redirect a vista de factura
```

---

### Línea 26-28: Facturas
```php
    // RF-58 al RF-64: Facturas
    Route::resource('facturas', FacturaController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
```

**Propósito**: Crear y ver facturas

**Línea 27 desglose - Resource:**

#### `.only(['index', 'create', 'store', 'show'])`
```
GET  /tecnico/facturas         → facturas.index  (mis facturas)
GET  /tecnico/facturas/create  → facturas.create (formulario crear)
POST /tecnico/facturas         → facturas.store  (guardar factura)
GET  /tecnico/facturas/5       → facturas.show   (detalle)
```

**Por qué no edit/update/delete:**
- Facturas son histórico (no se editan)
- Si error: se anula y crea otra

**Línea 28 desglose - PDF:**

#### `GET /facturas/{factura}/pdf`
- **URL**: `/tecnico/facturas/5/pdf`
- **Retorna**: Descarga PDF de factura

---

### Línea 30-32: Consumo de Materiales
```php
    // RF-XX: Consumo de Materiales
    Route::get('ordenes/{ordene}/materiales', [ConsumoMaterialController::class, 'show'])->name('consumo-materiales.show');
    Route::post('ordenes/{ordene}/materiales', [ConsumoMaterialController::class, 'store'])->name('consumo-materiales.store');
    Route::delete('consumo-materiales/{consumo}', [ConsumoMaterialController::class, 'destroy'])->name('consumo-materiales.destroy');
```

**Propósito**: Registrar materiales usados en orden (consumo)

**Línea 31 desglose - Ver Materiales:**

#### `GET /ordenes/{ordene}/materiales`
- **URL**: `/tecnico/ordenes/5/materiales`
- **Qué hace**: Muestra formulario + lista de materiales ya consumidos

**Línea 32 desglose - Agregar Consumo:**

#### `POST /ordenes/{ordene}/materiales`
- **URL**: `/tecnico/ordenes/5/materiales`
- **Qué hace**: Registra que se usó un producto en esta orden
- **Impacto**: Reduce inventario automáticamente
- **Ejemplo**: Usó 2 latas de pintura en orden 5

**Línea 33 desglose - Eliminar Consumo:**

#### `DELETE /consumo-materiales/{consumo}`
- **URL**: `/tecnico/consumo-materiales/15` (ID de consumo, no orden)
- **Qué hace**: Quita consumo registrado
- **Impacto**: Restaura inventario (suma cantidad de vuelta)

**Flujo:**
```
1. Técnico abre orden
2. Ve sección "Consumo de Materiales"
3. Agrega: "Pintura Roja" (2 latas)
   → POST /ordenes/5/materiales
   → Inventario: 50 → 48 latas
4. Si se equivocó:
   → DELETE /consumo-materiales/15
   → Inventario: 48 → 50 latas (restaurado)
```

---

### Línea 35-38: Vehículos
```php
    // RF-21 al RF-23, RF-25, RF-26: Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'show']);
    Route.patch('vehiculos/{vehiculo}/estado', [VehiculoController::class, 'actualizarEstado'])->name('vehiculos.estado');
    Route::post('vehiculos/{vehiculo}/foto', [VehiculoController::class, 'subirFoto'])->name('vehiculos.foto');
```

**Propósito**: Ver vehículos y actualizar estado

**Línea 36 desglose - Resource:**

#### `.only(['index', 'show'])`
```
GET /tecnico/vehiculos     → vehiculos.index
GET /tecnico/vehiculos/5   → vehiculos.show
```

**Línea 37 desglose - Actualizar Estado:**

#### `PATCH /vehiculos/{vehiculo}/estado`
- **URL**: `/tecnico/vehiculos/5/estado`
- **Qué hace**: Actualiza estado del vehículo directamente
- **Estados posibles**: "Disponible", "En Reparación", "Listo", "Entregado"

**Línea 38 desglose - Subir Foto:**

#### `POST /vehiculos/{vehiculo}/foto`
- **URL**: `/tecnico/vehiculos/5/foto`
- **Qué hace**: Sube foto de vehículo (antes/durante/después de reparación)
- **Almacenamiento**: `storage/app/public/vehiculos/`

---

### Línea 40-41: Historial
```php
    // RF-20: Historial
    Route::resource('historial', HistorialVehiculoController::class)->only(['index', 'show']);
```

**Propósito**: Ver historial de reparaciones

**Rutas:**
```
GET /tecnico/historial     → historial.index  (ver todos)
GET /tecnico/historial/5   → historial.show   (detalle)
```

---

### Línea 43-44: Ventas
```php
    // RF-74: Ventas (técnico registra)
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('ventas/get-vehiculos/{idCliente}', [VentaController::class, 'getVehiculosCliente'])->name('ventas.get-vehiculos');
```

**Propósito**: Técnico registra ventas de productos

**Línea 43 desglose - Resource:**

#### `.only(['index', 'create', 'store', 'show'])`
```
GET  /tecnico/ventas         → ventas.index
GET  /tecnico/ventas/create  → ventas.create  (formulario vender)
POST /tecnico/ventas         → ventas.store   (guardar venta)
GET  /tecnico/ventas/5       → ventas.show    (detalle venta)
```

**Línea 44 desglose - Get Vehículos AJAX:**

#### `GET /ventas/get-vehiculos/{idCliente}`
- **URL**: `/tecnico/ventas/get-vehiculos/3`
- **Qué hace**: Retorna JSON de vehículos del cliente
- **Uso**: Formulario de venta (dropdown dinámico)

**Flujo AJAX:**
```javascript
// Usuario selecciona cliente en formulario
document.querySelector('#cliente').addEventListener('change', (e) => {
    fetch(`/tecnico/ventas/get-vehiculos/${e.target.value}`)
    .then(r => r.json())
    .then(vehiculos => {
        // Populate dropdown de vehículos
        vehiculos.forEach(v => {
            // Agregar option al select
        });
    });
});
```

---

### Línea 45: Cierre
```php
});
```

Cierra grupo de rutas.

---

## Comparación: Técnico vs Admin vs Cliente

### Admin:
- Gestión completa (crear, editar, eliminar)
- Panel de reportes
- Acceso a inventario
- Acceso a usuarios

### Técnico:
- **Órdenes**: Ver y actualizar estado
- **Materiales**: Registrar consumo
- **Vehículos**: Ver y actualizar estado
- **Facturas**: Crear y ver
- **Ventas**: Registrar

### Cliente:
- **Vehículos**: CRUD propios
- **Cotizaciones**: Ver y responder
- **Facturas**: Ver y marcar pagadas
- **Citas**: Crear y ver
- **Historial**: Ver

---

## Controladores Requeridos

```
✓ app/Http/Controllers/Tecnico/DashboardController.php
✓ app/Http/Controllers/Tecnico/CitaController.php
✓ app/Http/Controllers/Tecnico/OrdenTrabajoController.php
✓ app/Http/Controllers/Tecnico/ConsumoMaterialController.php
✓ app/Http/Controllers/Tecnico/VehiculoController.php
✓ app/Http/Controllers/Tecnico/HistorialVehiculoController.php
✓ app/Http/Controllers/Tecnico/VentaController.php
✓ app/Http/Controllers/Tecnico/FacturaController.php
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
