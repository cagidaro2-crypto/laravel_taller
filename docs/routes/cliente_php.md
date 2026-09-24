# 📄 routes/cliente.php - Documentación Línea por Línea

## Resumen General
Define todas las rutas del panel cliente. Cada ruta está protegida por middleware de autenticación y validación de rol (solo Cliente).

**Archivo**: `routes/cliente.php`  
**Responsabilidad**: Definir endpoints (URLs) para el panel de cliente  
**Dependencias**: 
- `Laravel\Routing\Router` (implícito)
- `App\Http\Controllers\Cliente\*` (todos los controladores cliente)
- `Middleware: ['auth', 'role:Cliente']`

**Si se borra este archivo**: El sistema no tendrá rutas de cliente, generando error 404 en todas las URLs `/cliente/*`.

---

## Línea por Línea

### Línea 1-8: Imports
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\VehiculoController;
use App\Http\Controllers\Cliente\CotizacionController;
use App\Http\Controllers\Cliente\HistorialVehiculoController;
use App\Http\Controllers\Cliente\CitaController;
use App\Http\Controllers\Cliente\NotificacionController;
use App\Http\Controllers\Cliente\FacturaController;
```

**Propósito**: Apertura PHP e importación de controladores y Route facade  
**Línea 3**: Importa `Route` para usar métodos como `Route::prefix()`, `Route::get()`, etc.  
**Línea 4-10**: Importa 7 controladores específicos del panel cliente

**Desglose imports:**
- `Route` → Fachada para definir rutas
- `DashboardController` → Controlador del dashboard cliente
- `VehiculoController` → Gestión de vehículos del cliente
- `CotizacionController` → Ver y responder cotizaciones
- `HistorialVehiculoController` → Ver historial de reparaciones
- `CitaController` → Agendar y ver citas
- `NotificacionController` → Ver y marcar notificaciones
- `FacturaController` → Ver facturas y marcar pagadas

**Dependencias:**
- Todos estos controladores DEBEN existir en `app/Http/Controllers/Cliente/`

**Qué sucede si falta un controlador:**
- Error: "Class not found"
- Ruta que lo use fallará

---

### Línea 12: Grupo de Rutas
```php
Route::prefix('cliente')->name('cliente.')->middleware(['auth', 'role:Cliente'])->group(function () {
```

**Propósito**: Define grupo con prefijo 'cliente', nombre 'cliente.', y middlewares de protección

**Desglose:**
- `Route::prefix('cliente')` → Prefijo `/cliente` en todas las rutas
- `.name('cliente.')` → Prefijo 'cliente.' en nombres de ruta
- `.middleware(['auth', 'role:Cliente'])` → Protección: debe estar autenticado Y tener rol Cliente
- `.group(function () { ... })` → Agrupa múltiples rutas

**Orden de ejecución:**
```
Cliente accede a /cliente/dashboard
  ↓
1. Middleware 'auth' valida: ¿Sesión?
   - NO → redirect a /login
2. Middleware 'role:Cliente' valida: ¿Rol es 'Cliente'?
   - NO → Error 403 Forbidden
3. Se ejecuta DashboardController@index
```

---

### Línea 14-15: Dashboard
```php
    // RF-05
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

**Propósito**: Ruta principal del panel cliente

**Rutas generadas:**
```
GET /cliente/dashboard → cliente.dashboard
```

**Dependencias:**
- `DashboardController` importado
- Método `index()` en controlador
- Vista `resources/views/cliente/dashboard.blade.php`

---

### Línea 17-21: Notificaciones
```php
    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones');
    Route::post('/notificaciones/{notificacion}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcar-leida');
    Route::post('/notificaciones/marcar-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-leidas');
    Route::get('/notificaciones/api/no-leidas', [NotificacionController::class, 'contadorNoLeidas'])->name('notificaciones.contador');
```

**Propósito**: Gestión de notificaciones por email

**Rutas generadas:**
```
GET  /cliente/notificaciones                    → notificaciones
POST /cliente/notificaciones/5/leida            → notificaciones.marcar-leida
POST /cliente/notificaciones/marcar-leidas      → notificaciones.marcar-leidas
GET  /cliente/notificaciones/api/no-leidas     → notificaciones.contador (retorna JSON)
```

**Línea 20 desglose (`/leida`):**
- **URL**: `/cliente/notificaciones/5/leida`
- **`{notificacion}`**: Parámetro (ID de notificación)
- **Método**: POST (modifica estado)
- **Qué hace**: Marca una notificación como leída

**Línea 21 desglose (`/marcar-leidas`):**
- **URL**: `/cliente/notificaciones/marcar-leidas`
- **Sin parámetro**: No toma ID específico
- **Qué hace**: Marca TODAS las notificaciones como leídas

**Línea 22 desglose (`/api/no-leidas`):**
- **URL**: `/cliente/notificaciones/api/no-leidas`
- **Método**: GET
- **Retorna**: JSON (probablemente `{ count: 5 }`)
- **Uso**: AJAX para actualizar badge de notificaciones en navbar

**Dependencias:**
- `NotificacionController` con métodos: `index()`, `marcarLeida()`, `marcarTodasLeidas()`, `contadorNoLeidas()`
- Tabla `notificaciones` en BD

---

### Línea 23-25: Vehículos
```php
    // RF-17 al RF-20, RF-25, RF-26: Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('vehiculos/{vehiculo}/foto', [VehiculoController::class, 'subirFoto'])->name('vehiculos.foto');
```

**Propósito**: Gestión de vehículos del cliente

**Línea 24 desglose - Resource:**

#### `.only(['index', 'create', 'store', 'show', 'edit', 'update'])`
- **Rutas**: CRUD completo excepto delete (clientes no pueden eliminar vehículos)
- **URLs**:
```
GET    /cliente/vehiculos              → vehiculos.index  (mis vehículos)
GET    /cliente/vehiculos/create       → vehiculos.create (agregar vehículo)
POST   /cliente/vehiculos              → vehiculos.store  (guardar vehículo)
GET    /cliente/vehiculos/5            → vehiculos.show   (detalle vehículo)
GET    /cliente/vehiculos/5/edit       → vehiculos.edit   (editar vehículo)
PUT    /cliente/vehiculos/5            → vehiculos.update (actualizar vehículo)
```

**Línea 25 desglose - Foto:**

#### `Route::post('vehiculos/{vehiculo}/foto', ...)`
- **URL**: `/cliente/vehiculos/5/foto`
- **Método**: POST
- **`{vehiculo}`**: Parámetro (ID de vehículo)
- **Qué hace**: Sube foto de vehículo
- **Almacenamiento**: Probablemente en `storage/app/public/vehiculos/`

**Dependencias:**
- `VehiculoController` con métodos: `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `subirFoto()`
- Tabla `vehiculos` con FK a `clientes`
- Tabla `vehiculo_fotos` o columna `foto` en `vehiculos`

---

### Línea 27-30: Cotizaciones
```php
    // RF-43 al RF-45: Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class)->only(['index', 'show']);
    Route::match(['patch', 'put'], 'cotizaciones/{cotizacione}/aprobar', [CotizacionController::class, 'aprobar'])->name('cotizaciones.aprobar');
    Route::match(['patch', 'put'], 'cotizaciones/{cotizacione}/rechazar', [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');
```

**Propósito**: Ver cotizaciones y responder (aprobar/rechazar)

**Línea 28 desglose - Resource:**

#### `.only(['index', 'show'])`
- **Rutas**: Solo lectura (cliente no crea cotizaciones)
- **URLs**:
```
GET /cliente/cotizaciones     → cotizaciones.index  (mis cotizaciones)
GET /cliente/cotizaciones/5   → cotizaciones.show   (detalle cotización)
```

**Línea 29-30 desglose - Responder:**

#### `Route::match(['patch', 'put'], ...)`
- **Métodos**: PATCH o PUT (actualización parcial)
- **URLs**:
```
PATCH /cliente/cotizaciones/5/aprobar   → cotizaciones.aprobar
PUT   /cliente/cotizaciones/5/aprobar   → cotizaciones.aprobar
PATCH /cliente/cotizaciones/5/rechazar  → cotizaciones.rechazar
PUT   /cliente/cotizaciones/5/rechazar  → cotizaciones.rechazar
```

**Qué significa `match(['patch', 'put'])`:**
```php
// Acepta BOTH métodos HTTP
// Útil porque algunos formularios usan PUT, otros PATCH

// Sin match, tendrías que definir dos rutas:
Route::patch('cotizaciones/{cota}/aprobar', ...);
Route::put('cotizaciones/{cota}/aprobar', ...);
```

**Dependencias:**
- `CotizacionController` con métodos: `index()`, `show()`, `aprobar()`, `rechazar()`
- Tabla `cotizaciones` con FK a clientes

---

### Línea 32-35: Facturas
```php
    // RF-58 al RF-64: Facturas
    Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
    Route::post('facturas/{factura}/marcar-pagada', [FacturaController::class, 'marcarPagada'])->name('facturas.marcar-pagada');
```

**Propósito**: Ver facturas y marcar como pagadas

**Rutas generadas:**
```
GET  /cliente/facturas              → facturas.index        (mis facturas)
GET  /cliente/facturas/5            → facturas.show         (detalle factura)
GET  /cliente/facturas/5/pdf        → facturas.pdf          (descargar PDF)
POST /cliente/facturas/5/marcar-pagada → facturas.marcar-pagada (NEW FEATURE)
```

**Línea 35 desglose - Pago:**

#### `Route::post('facturas/{factura}/marcar-pagada', ...)`
- **URL**: `/cliente/facturas/5/marcar-pagada`
- **Método**: POST
- **Qué hace**: Marca factura como pagada (crea entrada en tabla `pagos`)
- **Retorna**: Probablemente JSON con datos actualizados
- **AJAX**: Usado desde botón con JavaScript

**Cómo funciona típicamente:**
```javascript
// En vista
<button onclick="marcarPagada(5)">Marcar como Pagada</button>

<script>
function marcarPagada(facturaId) {
    fetch(`/cliente/facturas/${facturaId}/marcar-pagada`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').value }
    })
    .then(r => r.json())
    .then(data => {
        // Actualizar página
        document.querySelector('.badge-status').textContent = 'Pagada';
        document.querySelector('.total-pagado').textContent = data.total;
    });
}
</script>
```

**Dependencias:**
- `FacturaController` con métodos: `index()`, `show()`, `pdf()`, `marcarPagada()`
- Tabla `facturas` con estado
- Tabla `pagos` para registrar pagos

---

### Línea 37-38: Historial
```php
    // RF-20: Historial
    Route::resource('historial', HistorialVehiculoController::class)->only(['index', 'show']);
```

**Propósito**: Ver historial de reparaciones de vehículos

**Rutas generadas:**
```
GET /cliente/historial     → historial.index  (ver todos historial)
GET /cliente/historial/5   → historial.show   (ver detalle historial)
```

**Dependencias:**
- `HistorialVehiculoController` con métodos: `index()`, `show()`
- Tabla `historial_vehiculo`

---

### Línea 40-42: Citas
```php
    // RF-29 al RF-33: Citas
    Route::resource('citas', CitaController::class)->only(['index', 'create', 'store', 'show']);
    Route::match(['patch', 'put'], 'citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
```

**Propósito**: Agendar citas y verlas

**Línea 41 desglose - Resource:**

#### `.only(['index', 'create', 'store', 'show'])`
- **Rutas**: Crear y ver citas (sin editar/eliminar)
- **URLs**:
```
GET  /cliente/citas           → citas.index   (mis citas)
GET  /cliente/citas/create    → citas.create  (formulario agendar)
POST /cliente/citas           → citas.store   (guardar cita)
GET  /cliente/citas/5         → citas.show    (detalle cita)
```

**Línea 42 desglose - Cancelar:**

#### `Route::match(['patch', 'put'], 'citas/{cita}/cancelar', ...)`
- **URL**: `/cliente/citas/5/cancelar`
- **Métodos**: PATCH o PUT
- **Qué hace**: Marca cita como cancelada

**Dependencias:**
- `CitaController` con métodos: `index()`, `create()`, `store()`, `show()`, `cancelar()`
- Tabla `citas`

---

### Línea 43: Cierre
```php
});
```

**Propósito**: Cierra grupo de rutas (matching con línea 12)

---

## Resumen de Diferencias: Admin vs Cliente

### Rutas Admin (admin.php):
- Gestión completa: crear, editar, eliminar usuarios, órdenes, facturas, etc.
- Acceso a reportes
- Panel de inventario
- Acceso a ventas

### Rutas Cliente (cliente.php):
- CRUD de vehículos (sus propios vehículos)
- Solo lectura de cotizaciones (con responder: aprobar/rechazar)
- Solo lectura de facturas (con marcar pagada)
- Agendar citas
- Ver historial de reparaciones
- Gestionar notificaciones

**Patrón de seguridad:** `middleware(['auth', 'role:Cliente'])` asegura que solo clientes autenticados accedan

---

## Controladores Requeridos

```
✓ app/Http/Controllers/Cliente/DashboardController.php
✓ app/Http/Controllers/Cliente/VehiculoController.php
✓ app/Http/Controllers/Cliente/CotizacionController.php
✓ app/Http/Controllers/Cliente/HistorialVehiculoController.php
✓ app/Http/Controllers/Cliente/CitaController.php
✓ app/Http/Controllers/Cliente/NotificacionController.php
✓ app/Http/Controllers/Cliente/FacturaController.php
```

---

## Errores Comunes

### ❌ Error: 404 en /cliente/vehiculos
```
Causa: Ruta no existe o controlador no importado
Solución:
  php artisan route:list | grep cliente
  ls app/Http/Controllers/Cliente/
```

### ❌ Error: 403 Forbidden
```
Causa: Middleware 'role:Cliente' rechazó acceso
Solución: Verificar en BD que usuario tiene id_rol = rol Cliente
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
