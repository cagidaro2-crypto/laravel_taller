# 📄 routes/admin.php - Documentación Línea por Línea

## Resumen General
Este archivo define todas las rutas administrativas del sistema. Cada ruta está protegida por middleware de autenticación y validación de rol (solo Administrador).

**Archivo**: `routes/admin.php`  
**Responsabilidad**: Definir endpoints (URLs) para el panel administrativo  
**Dependencias**: 
- `Laravel\Routing\Router` (implícito)
- `App\Http\Controllers\Admin\*` (todos los controladores admin)
- `Middleware: ['auth', 'role:Administrador']`

**Si se borra este archivo**: El sistema no tendrá rutas administrativas, generando error 404 en todas las URLs `/admin/*`.

---

## Línea por Línea

### Línea 1-2
```php
<?php

```
**Propósito**: Declaración de apertura de archivo PHP  
**Dependencias**: Ninguna  
**Qué sucede si se borra**: Error fatal - archivo no sería reconocido como PHP  
**Cómo arreglarlo**: Debe estar al principio de TODOS los archivos PHP

---

### Línea 3: Uso de Facades
```php
use Illuminate\Support\Facades\Route;
```
**Propósito**: Importa la clase `Route` para usar métodos como `Route::prefix()`, `Route::resource()`, etc.  
**Qué es Facade**: Alias simplificado para acceder a clases complejas de Laravel  

**Línea por línea:**
- `use` → Declara que este archivo usará la clase Route
- `Illuminate\Support\Facades\Route` → Ruta completa de la clase
- `Route` → Alias abreviado para usar `Route::` en el código

**Dependencias**:
- Composer debe tener `laravel/framework` instalado
- `vendor/laravel/framework/src/Illuminate/Support/Facades/Route.php` debe existir

**Qué sucede si se borra**: Error "Class 'Route' not found"  
**Cómo arreglarlo**: Agregar línea de `use` nuevamente

**Variaciones válidas:**
```php
use Illuminate\Support\Facades\Route as R;  // Alias personalizado
Route::get(...);  // Usar sin import (fallará si no está importado)
```

---

### Línea 4-16: Importación de Controladores
```php
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
```

**Propósito**: Importar todas las clases controlador usadas en esta ruta  
**Qué es un Controlador**: Clase PHP que procesa la lógica de negocio (GET, POST, etc.)

**Línea por línea (ejemplo DashboardController):**
```php
use App\Http\Controllers\Admin\DashboardController;
// use → Importar
// App\Http\Controllers\Admin\ → Namespace (carpeta lógica)
// DashboardController → Nombre de la clase
```

**Ubicación física en disco:**
```
app/Http/Controllers/Admin/DashboardController.php
// Carpeta: app/Http/Controllers/Admin/
// Archivo: DashboardController.php
```

**Dependencias:**
- Cada controlador DEBE existir físicamente
- PSR-4 autoloading de Composer (en `composer.json`)

**Qué sucede si falta un controlador:**
- Error: `Class 'App\Http\Controllers\Admin\UsuarioController' not found`
- La ruta que intente usar ese controlador fallará

**Si se borra una línea (ej: UsuarioController):**
- Las rutas de usuarios seguirán definidas en el archivo
- Cuando intentes acceder a `/admin/usuarios`, error 500: "Class not found"

**Cómo arreglarlo:**
- Verifica que el archivo existe: `app/Http/Controllers/Admin/UsuarioController.php`
- Verifica namespace en controlador: `namespace App\Http\Controllers\Admin;`
- Reagrega línea use: `use App\Http\Controllers\Admin\UsuarioController;`

---

### Línea 18: Grupo de Rutas con Prefijo
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Administrador'])->group(function () {
```

**Propósito**: Define un grupo de rutas con prefijo 'admin', nombre 'admin.', y middlewares de protección

**Desglose línea por línea:**

#### `Route::prefix('admin')`
- **Qué hace**: Agrega prefijo `/admin` a todas las rutas dentro del grupo
- **Ejemplo**: `Route::get('/dashboard', ...)` → `/admin/dashboard`
- **Dependencias**: Ninguna
- **Qué sucede si falta**: Todas las rutas serían `/dashboard`, `/usuarios`, etc. (sin /admin)

#### `.name('admin.')`
- **Qué hace**: Agrega prefijo 'admin.' al nombre de cada ruta
- **Ejemplo**: `Route::get(...)->name('dashboard')` → `'admin.dashboard'`
- **Uso en vistas**: `route('admin.dashboard')` genera URL correcta
- **Qué sucede si falta**: Nombres de ruta se verían como `'dashboard'`, `'usuarios'`, etc.

#### `.middleware(['auth', 'role:Administrador'])`
- **Qué hace**: Aplica dos middlewares a TODAS las rutas del grupo
- **`auth`** → Valida que usuario esté autenticado (en sesión)
- **`role:Administrador`** → Valida que usuario tenga rol 'Administrador'

**Orden de ejecución middleware:**
```
Usuario hace request a /admin/dashboard
  ↓
1. Middleware 'auth' valida: ¿Sesión activa?
   - SÍ → continúa
   - NO → redirect a /login
  ↓
2. Middleware 'role:Administrador' valida: ¿Rol es 'Administrador'?
   - SÍ → continúa
   - NO → error 403 (Forbidden)
  ↓
3. Se ejecuta controlador (DashboardController@index)
```

**Ubicación de middlewares:**
```
app/Http/Middleware/Authenticate.php
app/Http/Middleware/CheckRole.php
```

#### `.group(function () { ... })`
- **Qué hace**: Agrupar múltiples rutas con los mismos prefijos/middlewares
- **Cierre**: Línea final `});` cierra el grupo
- **Qué sucede si falta grupo**: Deberías repetir prefijo/middleware en cada ruta

**Ejemplo sin grupo (MALO):**
```php
Route::prefix('admin')->middleware(['auth', 'role:Administrador'])->get('/dashboard', [DashboardController::class, 'index']);
Route::prefix('admin')->middleware(['auth', 'role:Administrador'])->get('/usuarios', [UsuarioController::class, 'index']);
// ❌ Repetitivo y mantener es difícil
```

**Ejemplo con grupo (BUENO):**
```php
Route::prefix('admin')->middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    // ✅ Limpio y DRY (Don't Repeat Yourself)
});
```

**Dependencias:**
- Middlewares DEBEN existir en `app/Http/Middleware/`
- Controladores DEBEN estar importados con `use`

**Qué sucede si se borra este línea:**
- Error sintáctico - falta `group()`
- Todas las rutas definidas abajo necesitarían redefinir prefix/middleware

**Cómo arreglarlo:**
- Restaurar línea completa
- Asegurar que `.group(function () {` esté presente
- Asegurar que `});` cierre el grupo al final

---

### Línea 20: Comentario - Dashboard
```php
    // RF-05: Dashboard
```
**Propósito**: Referencia a requisito funcional (RF-05)  
**Qué es RF**: Requisito Funcional del sistema  
**Qué sucede si se borra**: Ningún impacto funcional (es solo comentario)  
**Utilidad**: Trazabilidad entre código y documento de requisitos

---

### Línea 21: Ruta Dashboard
```php
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

**Propósito**: Define ruta GET para el dashboard administrativo

**Desglose línea por línea:**

#### `Route::get('/dashboard', ...)`
- **Qué hace**: Crea ruta que responde a petición HTTP GET
- **URL final**: `/admin/dashboard` (por el prefijo)
- **Método HTTP**: GET (obtener información, no modificar)
- **Métodos alternativos**: `post()`, `put()`, `patch()`, `delete()`

#### `[DashboardController::class, 'index']`
- **Qué hace**: Especifica qué controlador y método ejecutar
- **Sintaxis moderna**: `ControllerClass::class` es más segura que string `'ControllerController'`
- **Equivalente antiguo**: `'App\Http\Controllers\Admin\DashboardController@index'`
- **Qué significa**: Llamar método `index()` de clase `DashboardController`

**Ejecución:**
```php
// En app/Http/Controllers/Admin/DashboardController.php
public function index()
{
    $usuarios = Usuario::count();
    $ordenes = OrdenTrabajo::count();
    return view('admin.dashboard', compact('usuarios', 'ordenes'));
}
```

#### `->name('dashboard')`
- **Qué hace**: Asigna nombre 'dashboard' a la ruta
- **Nombre final**: 'admin.dashboard' (por el prefijo del grupo)
- **Uso en vistas**: `<a href="{{ route('admin.dashboard') }}">Dashboard</a>`
- **Uso en controladores**: `redirect()->route('admin.dashboard')`
- **Ventaja**: Si cambias URL, el nombre sigue siendo igual (mejor mantenimiento)

**Qué sucede si se borra `->name('dashboard')`:**
- Ruta sigue funcionando, pero:
- No puedes usar `route('admin.dashboard')` → Error "Route not found"
- Tienes que hardcodear URL: `href="/admin/dashboard"` → MALO (frágil)

**Dependencias:**
- `DashboardController` DEBE estar importado arriba
- `DashboardController` DEBE tener método `public function index()`

**Qué sucede si controlador no existe:**
- Error 500: "Class not found"

**Cómo arreglarlo:**
- Verifica que DashboardController.php existe en `app/Http/Controllers/Admin/`
- Verifica que tiene método `public function index() { ... }`
- Verifica que está importado: `use App\Http\Controllers\Admin\DashboardController;`

---

### Línea 23: Recurso de Usuarios
```php
    Route::resource('usuarios', UsuarioController::class);
```

**Propósito**: Genera 7 rutas REST automáticas para gestión de usuarios

**Qué es un Resource Route:**
Laravel genera estas rutas automáticamente:

```
GET      /admin/usuarios              → index()      (listar)
GET      /admin/usuarios/create       → create()     (formulario crear)
POST     /admin/usuarios              → store()      (guardar)
GET      /admin/usuarios/{usuario}    → show()       (detalle)
GET      /admin/usuarios/{usuario}/edit → edit()    (formulario editar)
PUT      /admin/usuarios/{usuario}    → update()     (actualizar)
DELETE   /admin/usuarios/{usuario}    → destroy()    (eliminar)
```

**Desglose línea por línea:**

#### `Route::resource('usuarios', UsuarioController::class)`
- **`resource`** → Método que genera 7 rutas REST
- **`'usuarios'`** → Nombre del recurso (plural)
- **`UsuarioController::class`** → Controlador que maneja todo

**URL resultante con prefijo 'admin':**
```
GET      /admin/usuarios              → admin.usuarios.index
GET      /admin/usuarios/create       → admin.usuarios.create
POST     /admin/usuarios              → admin.usuarios.store
GET      /admin/usuarios/123          → admin.usuarios.show
GET      /admin/usuarios/123/edit     → admin.usuarios.edit
PUT      /admin/usuarios/123          → admin.usuarios.update
DELETE   /admin/usuarios/123          → admin.usuarios.destroy
```

**Equivalente sin usar resource (MALO - repetitivo):**
```php
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
```

**Con resource (BUENO - conciso):**
```php
Route::resource('usuarios', UsuarioController::class);
```

**Dependencias:**
- `UsuarioController` DEBE estar importado
- `UsuarioController` DEBE tener todos estos métodos públicos: `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()`
- Modelo `Usuario` se inyecta automáticamente por type-hinting en rutas con `{usuario}`

**Qué sucede si falta un método en el controlador:**
- Error 500 cuando accedes a esa ruta
- Ej: Si no existe `destroy()`, error al hacer DELETE

**Qué sucede si se borra esta línea:**
- 7 rutas desaparecen
- `/admin/usuarios/` → 404 Not Found
- Panel de usuarios se rompe completamente

**Cómo arreglarlo:**
- Restaurar línea
- Asegurar UsuarioController tiene todos los métodos necesarios

**Variaciones válidas:**

```php
// Generar solo algunas rutas
Route::resource('usuarios', UsuarioController::class)->only(['index', 'show', 'create', 'store']);

// Excluir algunas rutas (ej: no permitir eliminar)
Route::resource('usuarios', UsuarioController::class)->except(['destroy']);

// Cambiar parámetro binding
Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'user']);
```

---

### Línea 25: Servicios
```php
    Route::resource('servicios', ServicioController::class);
```

**Propósito**: Genera 7 rutas REST para servicios (reparación, revisión, etc.)  
**URL**: `/admin/servicios/*`  
**Controlador**: `App\Http\Controllers\Admin\ServicioController`  
**Dependencias**: Mismo patrón que usuarios

**Qué sucede si se borra:**
- Servicios desaparecen del panel admin
- `/admin/servicios` → 404

---

### Línea 27-28: Productos (Catálogo)
```php
    Route::resource('productos', ProductoController::class);
    Route::patch('productos/{producto}/desactivar', [ProductoController::class, 'desactivar'])->name('productos.desactivar');
```

**Propósito**: 
- Línea 27: 7 rutas REST estándar para productos
- Línea 28: Ruta adicional custom para desactivar producto

**Desglose línea 28:**

#### `Route::patch(...)`
- **Qué hace**: Petición PATCH (actualización parcial)
- **Diferencia PATCH vs PUT**:
  - `PUT`: Reemplaza todo el recurso
  - `PATCH`: Actualiza solo campos específicos (este caso: solo `activo = false`)

#### `'productos/{producto}/desactivar'`
- **URL**: `/admin/productos/5/desactivar`
- **`{producto}`**: Parámetro dinámico (id del producto a desactivar)
- **Route Model Binding**: Laravel inyecta automáticamente el objeto Producto

#### `[ProductoController::class, 'desactivar']`
- Llama método `desactivar()` en ProductoController
- Método típico:
```php
public function desactivar(Producto $producto)
{
    $producto->update(['activo' => false]);
    return redirect()->route('admin.productos.index')->with('success', 'Producto desactivado');
}
```

#### `->name('productos.desactivar')`
- Nombre: 'admin.productos.desactivar'
- Uso en vista: `form action="{{ route('admin.productos.desactivar', $producto) }}"`

**Qué sucede si se borra:**
- No se puede desactivar productos vía UI
- `/admin/productos/5/desactivar` → 404

**Cómo arreglarlo:**
- Restaurar línea
- Asegurar `ProductoController::desactivar()` existe

---

### Línea 30-31: Proveedores
```php
    Route::resource('proveedores', ProveedorController::class);
```

**Propósito**: 7 rutas REST para gestión de proveedores  
**URL**: `/admin/proveedores/*`

---

### Línea 33-35: Inventario (Especial)
```php
    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('inventario/{inventario}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::patch('inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');
```

**Propósito**: Rutas PERSONALIZADAS para inventario (no es resource completo)

**¿Por qué no usar resource completo?**
- Inventario es de solo lectura + actualizar cantidad
- No hay creación/eliminación normal (se gestiona vía ConsumoMaterial y compras)

**Rutas generadas:**
```
GET    /admin/inventario            → inventario.index     (listar productos)
GET    /admin/inventario/5/edit     → inventario.edit      (formulario editar cantidad)
PATCH  /admin/inventario/5          → inventario.update    (guardar nueva cantidad)
```

**Dependencias:**
- `InventarioController` debe tener: `index()`, `edit()`, `update()`

**Qué sucede si se borra:**
- Panel de inventario no funciona
- No se puede ver cantidad de productos

---

### Línea 37-38: Órdenes de Trabajo
```php
    Route::resource('ordenes', OrdenTrabajoController::class)->except(['destroy']);
    Route::post('ordenes/{ordene}/factura', [OrdenTrabajoController::class, 'generarFactura'])->name('ordenes.factura');
```

**Línea 37 desglose:**

#### `.except(['destroy'])`
- **Qué hace**: Genera 7 rutas REST EXCEPTO DELETE
- **Rutas resultantes**: index, create, store, show, edit, update (sin destroy)
- **Por qué**: No se eliminan órdenes, solo se archivan/cierran

**Línea 38 desglose:**

#### `Route::post('ordenes/{ordene}/factura', ...)`
- **Qué hace**: Crea ruta para convertir orden a factura
- **URL**: `/admin/ordenes/5/factura`
- **Método**: POST (modifica estado/crea factura)
- **`{ordene}`**: Parámetro (singular de ordenes, nombrado así por la BD)

#### `[OrdenTrabajoController::class, 'generarFactura']`
- Llama método `generarFactura()` en controlador
- Típicamente:
```php
public function generarFactura(OrdenTrabajo $ordene)
{
    $factura = Factura::create([
        'id_orden' => $ordene->id_orden,
        'numero_factura' => Factura::generarNumero(),
        // ... más campos
    ]);
    return redirect()->route('admin.facturas.show', $factura);
}
```

**Qué sucede si se borra `->except(['destroy'])`:**
- Aparecería ruta DELETE `/admin/ordenes/5`
- Usuarios podrían eliminar órdenes (PROBLEMA)
- Pérdida de auditoría

**Cómo arreglarlo:**
- Restaurar `.except(['destroy'])`
- O implementar `destroy()` para mostrar error "No permitido"

---

### Línea 40-42: Cotizaciones
```php
    Route::resource('cotizaciones', CotizacionController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('cotizaciones/{cotizacione}/factura', [CotizacionController::class, 'convertirFactura'])->name('cotizaciones.factura');
    Route::patch('cotizaciones/{cotizacione}/rechazar', [CotizacionController::class, 'rechazar'])->name('cotizaciones.rechazar');
```

**Línea 40 desglose:**

#### `.only(['index', 'create', 'store', 'show'])`
- **Qué hace**: Genera SOLO estas 4 rutas
- **Excluidas**: edit, update, destroy
- **Por qué**: Cotizaciones son histórico, no se editan después de creadas

**Rutas resultantes:**
```
GET    /admin/cotizaciones           → cotizaciones.index
GET    /admin/cotizaciones/create    → cotizaciones.create
POST   /admin/cotizaciones           → cotizaciones.store
GET    /admin/cotizaciones/5         → cotizaciones.show
```

**Línea 42 desglose:**

#### `.patch('cotizaciones/{cotizacione}/rechazar', ...)`
- **Qué hace**: Marca cotización como rechazada
- **URL**: `/admin/cotizaciones/5/rechazar`
- **`{cotizacione}`**: Nota singular (por tabla `cotizaciones` → modelo `Cotizacion`, pero Laravel lo nombra `cotizacione`)

**Qué sucede si se borra:**
- No se pueden rechazar cotizaciones
- Solo opción sería convertir a factura

---

### Línea 44-48: Facturas
```php
    Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
    Route::post('facturas/{factura}/pago', [FacturaController::class, 'registrarPago'])->name('facturas.pago');
```

**Rutas generadas:**
```
GET    /admin/facturas              → facturas.index    (listar)
GET    /admin/facturas/5            → facturas.show     (detalle)
GET    /admin/facturas/5/pdf        → facturas.pdf      (descargar PDF)
POST   /admin/facturas/5/pago       → facturas.pago     (registrar pago)
```

**Por qué no usar resource:**
- Facturas son histórico (no se editan/eliminan)
- Solo se ven, se generan PDF, y se registran pagos

**Línea 47 desglose (`/pdf`):**
- **Qué hace**: Genera PDF de factura
- Típicamente usa librería como `dompdf` o `mpdf`
- Retorna descarga: `return response()->download($pdf_path);`

**Línea 48 desglose (`/pago`):**
- **Qué hace**: Registra pago a factura
- Crea entrada en tabla `pagos`
- Actualiza `estado` de factura a "Pagada"

---

### Línea 50-51: Ventas
```php
    Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');
```

**Línea 50 desglose:**

#### `.except(['edit', 'update', 'destroy'])`
- **Rutas**: index, create, store, show (sin edit, update, destroy)
- **Por qué**: Ventas se crean y se ven, pero no se modifican (si error, se anulan)

**Línea 51 desglose:**

#### `.patch('ventas/{venta}/anular', ...)`
- **Qué hace**: Anula una venta (marca como anulada)
- **URL**: `/admin/ventas/5/anular`
- **Diferencia delete vs anular**:
  - `DELETE`: Elimina registro (pérdida de auditoría)
  - `ANULAR`: Marca como inactivo (auditoría preservada)

**Cómo funciona típicamente:**
```php
public function anular(Venta $venta)
{
    // Restaurar inventario
    foreach ($venta->detalles as $detalle) {
        $inventario = Inventario::where('id_producto', $detalle->id_producto)->first();
        $inventario->cantidad_disponible += $detalle->cantidad;
        $inventario->save();
    }
    
    // Marcar como anulada
    $venta->update(['estado' => 'Anulada']);
    
    return redirect()->route('admin.ventas.index')->with('success', 'Venta anulada');
}
```

---

### Línea 53-59: Reportes
```php
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/productividad', [ReporteController::class, 'productividad'])->name('reportes.productividad');
    Route::get('reportes/ingresos', [ReporteController::class, 'ingresos'])->name('reportes.ingresos');
    Route::get('reportes/consumo', [ReporteController::class, 'consumo'])->name('reportes.consumo');
    Route::get('reportes/exportar', [ReporteController::class, 'exportarCSV'])->name('reportes.exportar');
```

**Propósito**: Rutas para diferentes tipos de reportes (todas GET porque son solo consultas)

**Rutas generadas:**
```
GET    /admin/reportes                → reportes.index          (inicio reportes)
GET    /admin/reportes/productividad  → reportes.productividad  (productividad técnicos)
GET    /admin/reportes/ingresos       → reportes.ingresos       (ingresos por período)
GET    /admin/reportes/consumo        → reportes.consumo        (consumo de materiales)
GET    /admin/reportes/exportar       → reportes.exportar       (descargar CSV)
```

**Métodos en ReporteController:**
- `index()` → Muestra opciones de reportes
- `productividad()` → Calcula horas/órdenes completadas por técnico
- `ingresos()` → Suma facturas pagadas por rango de fechas
- `consumo()` → Muestra materiales más utilizados
- `exportarCSV()` → Exporta datos a archivo CSV

---

### Línea 61-62: Vehículos
```php
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'show']);
```

**Propósito**: Admin puede ver vehículos y detalle (solo lectura)

**Rutas generadas:**
```
GET    /admin/vehiculos              → vehiculos.index  (listar)
GET    /admin/vehiculos/5            → vehiculos.show   (detalle con ventas)
```

**Por qué solo index y show:**
- Admin no crea vehículos (eso hace el cliente)
- Admin solo ve información para reportes

---

### Línea 63: Cierre del Grupo
```php
});
```

**Propósito**: Cierra `Route::prefix('admin')->group(function () {` de la línea 18

**Qué sucede si falta:**
- Error de sintaxis PHP
- Todas las líneas después de la ruta anterior interpretarían incorrectamente

**Estructura:**
```php
Route::prefix('admin')->group(function () {
    // Rutas dentro del grupo ← heredan prefijo y middleware
    Route::get(...);
    Route::post(...);
});  ← Cierre OBLIGATORIO
```

---

## Resumen de Dependencias

### Controladores requeridos:
```
✓ app/Http/Controllers/Admin/DashboardController.php
✓ app/Http/Controllers/Admin/UsuarioController.php
✓ app/Http/Controllers/Admin/ServicioController.php
✓ app/Http/Controllers/Admin/ProductoController.php
✓ app/Http/Controllers/Admin/ProveedorController.php
✓ app/Http/Controllers/Admin/OrdenTrabajoController.php
✓ app/Http/Controllers/Admin/CotizacionController.php
✓ app/Http/Controllers/Admin/InventarioController.php
✓ app/Http/Controllers/Admin\FacturaController.php
✓ app/Http/Controllers/Admin/VentaController.php
✓ app/Http/Controllers/Admin/ReporteController.php
✓ app/Http/Controllers/Admin/VehiculoController.php
```

### Middlewares requeridos:
```
✓ app/Http/Middleware/Authenticate.php
✓ app/Http/Middleware/CheckRole.php
```

### Modelos implicados:
```
✓ Usuario (con relación rol)
✓ Rol (con nombre_rol = 'Administrador')
✓ Producto
✓ Servicio
✓ Proveedor
✓ OrdenTrabajo
✓ Cotizacion
✓ Factura
✓ Venta
✓ Vehiculo
```

---

## Errores Comunes y Soluciones

### ❌ Error: 404 Not Found en /admin/usuarios
**Causa**: Ruta no existe o controlador no está importado
**Solución**:
```bash
# Verificar ruta está registrada
php artisan route:list | grep usuarios

# Verificar controlador existe
ls app/Http/Controllers/Admin/UsuarioController.php
```

### ❌ Error: Class not found
**Causa**: Falta import de controlador
**Solución**: Agregar línea use en top del archivo

### ❌ Error: 403 Forbidden en /admin/dashboard
**Causa**: Usuario no tiene rol 'Administrador'
**Solución**: Verifica en BD que `usuarios.id_rol` apunta a rol "Administrador"

### ❌ Error: Route not defined [admin.dashboard]
**Causa**: Ruta existe pero no tiene `.name()`
**Solución**: Agregar `->name('dashboard')` a ruta

---

## Cómo Mantener Este Archivo

### Agregar nueva ruta:
```php
// Dentro de Route::prefix('admin')->group(function () {
Route::resource('nuevos', NuevoController::class);
```

### Agregar ruta custom:
```php
Route::post('ordenes/{ordene}/custom', [OrdenTrabajoController::class, 'metodoCustom'])->name('ordenes.custom');
```

### Remover ruta:
```php
// Simplemente eliminar la línea (pero asegúrate que no la usen en vistas)
// Buscar en vistas: grep -r "route('admin.usuarios')" resources/
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
