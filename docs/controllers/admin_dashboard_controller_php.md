# 📄 app/Http/Controllers/Admin/DashboardController.php - Documentación Línea por Línea

## Resumen General
Controlador que gestiona la página principal del administrador. Recopila estadísticas clave del sistema: órdenes pendientes, clientes activos, productos bajo stock, y citas programadas para hoy.

**Archivo**: `app/Http/Controllers/Admin/DashboardController.php`  
**Responsabilidad**: Procesar lógica para vista de dashboard administrativo  
**Ruta asociada**: `GET /admin/dashboard` (definida en `routes/admin.php`)  

**Dependencias:**
- `App\Http\Controllers\Controller` (clase base)
- `App\Models\Admin\Inventario`
- `App\Models\Cliente\Cliente`
- `App\Models\Tecnico\Cita`
- `App\Models\Tecnico\OrdenTrabajo`
- Base de datos (tablas: ordenes_trabajo, clientes, inventario, citas, estados_ot)

**Si se borra este archivo:**
- Error 500 cuando accedes a `/admin/dashboard`
- Panel administrativo no funciona
- Admin no puede ver estadísticas

---

## Línea por Línea

### Línea 1-2
```php
<?php

```
**Propósito**: Apertura de archivo PHP  
**Dependencias**: Ninguna  
**Qué sucede si se borra**: Error fatal - archivo no es PHP  
**Cómo arreglarlo**: Debe estar al principio de TODO archivo PHP

---

### Línea 3: Declaración de Namespace
```php
namespace App\Http\Controllers\Admin;
```

**Propósito**: Define el espacio de nombres para esta clase  
**Qué es Namespace**: Sistema de organización de clases (como carpetas lógicas)

**Desglose línea por línea:**
- `namespace` → Palabra clave para declarar namespace
- `App\Http\Controllers\Admin;` → Ruta lógica de la clase

**Ubicación física vs lógica:**
```
Física en disco: app/Http/Controllers/Admin/DashboardController.php
Namespace:      App\Http\Controllers\Admin;
```

**PSR-4 Autoloading:**
Laravel (via Composer) mapea:
- `App\` → carpeta `app/`
- `Http\` → carpeta `Http/`
- `Controllers\` → carpeta `Controllers/`
- `Admin\` → carpeta `Admin/`

**Qué sucede si se borra o cambia:**
- Clase se vuelve "inimitable"
- `use App\Http\Controllers\Admin\DashboardController;` fallará
- Error: "Class not found"

**Qué sucede si cambia a otro namespace:**
```php
namespace App\Controllers\Admin;  // ❌ INCORRECTO
// Ruta física: app/Http/Controllers/Admin/DashboardController.php
// Namespace: App\Controllers\Admin
// NO COINCIDEN → Error de autoloading
```

**Cómo arreglarlo:**
- Namespace DEBE coincidir con ruta de carpetas
- Si está en `app/Http/Controllers/Admin/`, namespace es `App\Http\Controllers\Admin;`
- Si lo mueves de carpeta, actualiza namespace

---

### Línea 4: Importación de Clase Base
```php
use App\Http\Controllers\Controller;
```

**Propósito**: Importa clase controladora base que proporciona métodos comunes

**Qué es Controller (clase base):**
```php
// app/Http/Controllers/Controller.php
class Controller
{
    // Métodos útiles para todos los controladores
    protected function authorize($ability, $resource = null) { ... }
    // Otros métodos inherited...
}
```

**Qué hereda DashboardController de Controller:**
- Métodos de autorización
- Métodos helper
- Acceso a Blade templates

**Línea por línea:**
- `use` → Importar
- `App\Http\Controllers\Controller` → Ruta de la clase
- Alias (sin alias, usaría `App\Http\Controllers\Controller`)

**Dependencias:**
- `app/Http/Controllers/Controller.php` DEBE existir
- Laravel lo crea automáticamente en nuevo proyecto

**Qué sucede si se borra:**
- Error: "Class 'App\Http\Controllers\Controller' not found"
- Línea 11 falla: `class DashboardController extends Controller`

**Cómo arreglarlo:**
- Agregar línea use nuevamente
- Verificar que `app/Http/Controllers/Controller.php` existe

---

### Línea 5-8: Importación de Modelos
```php
use App\Models\Admin\Inventario;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\OrdenTrabajo;
```

**Propósito**: Importar clases modelo para usar en el controlador

**Desglose cada línea:**

#### Línea 5: `use App\Models\Admin\Inventario;`
- **Qué es**: Modelo que representa tabla `inventario`
- **Ubicación**: `app/Models/Admin/Inventario.php`
- **Uso en código**: `Inventario::whereRaw(...)->count()`
- **Dependencias**: Tabla `inventario` en BD

#### Línea 6: `use App\Models\Cliente\Cliente;`
- **Qué es**: Modelo para tabla `clientes`
- **Ubicación**: `app/Models/Cliente/Cliente.php`
- **Uso en código**: `Cliente::count()`
- **Dependencias**: Tabla `clientes` en BD

#### Línea 7: `use App\Models\Tecnico\Cita;`
- **Qué es**: Modelo para tabla `citas`
- **Ubicación**: `app/Models/Tecnico/Cita.php`
- **Uso en código**: `Cita::whereDate(...)->where(...)->count()`
- **Dependencias**: Tabla `citas` en BD

#### Línea 8: `use App\Models\Tecnico\OrdenTrabajo;`
- **Qué es**: Modelo para tabla `ordenes_trabajo`
- **Ubicación**: `app/Models/Tecnico/OrdenTrabajo.php`
- **Uso en código**: `OrdenTrabajo::whereHas(...)->count()`
- **Dependencias**: Tabla `ordenes_trabajo` + relación `estado`

**Qué sucede si falta una importación:**
```php
// Sin: use App\Models\Admin\Inventario;

class DashboardController extends Controller {
    public function index() {
        Inventario::count();  // ❌ Error: "Class 'Inventario' not found"
    }
}
```

**Cómo arreglarlo:**
- Agregar línea `use` correspondiente
- O usar nombre completo: `App\Models\Admin\Inventario::count()`

**Qué sucede si el modelo NO existe en disco:**
- Línea se importa pero archivo falta
- Error cuando intentas usar: `Class App\Models\Admin\Inventario not found`

**Cómo verificar que existen:**
```bash
ls app/Models/Admin/Inventario.php
ls app/Models/Cliente/Cliente.php
ls app/Models/Tecnico/Cita.php
ls app/Models/Tecnico/OrdenTrabajo.php
```

---

### Línea 10: Declaración de Clase
```php
class DashboardController extends Controller
{
```

**Propósito**: Define clase `DashboardController` que hereda de `Controller`

**Desglose línea por línea:**
- `class` → Palabra clave para definir clase
- `DashboardController` → Nombre de la clase (DEBE coincidir con nombre de archivo)
- `extends Controller` → Hereda métodos/propiedades de `Controller`

**Nombre del archivo vs Clase:**
```
Archivo: DashboardController.php
Clase:   class DashboardController
         ✓ DEBEN coincidir (PSR-2)
```

**Qué es herencia (`extends`):**
```php
class Controller {
    public function authorize($ability) { ... }
}

class DashboardController extends Controller {
    // Hereda método authorize()
    // Puede usarlo: $this->authorize('view', $resource);
}
```

**Dependencias:**
- Clase `Controller` DEBE estar importada (línea 4)
- `app/Http/Controllers/Controller.php` DEBE existir

**Qué sucede si cambia nombre:**
```php
class AdminDashboard extends Controller { ... }
// Archivo sigue siendo DashboardController.php
// ❌ Mismatch → Laravel no lo reconoce
```

**Cómo arreglarlo:**
- Nombre de clase DEBE ser `DashboardController` (PSR-2)
- Si lo cambias, renombra archivo también

---

### Línea 11-26: Método index()
```php
    public function index()
    {
        $totalOrdenes  = OrdenTrabajo::whereHas('estado', fn($q) =>
            $q->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
        )->count();
        $totalClientes = Cliente::count();
        $bajosStock    = Inventario::whereRaw('cantidad <= stock_minimo')->count();
        $citasHoy      = Cita::whereDate('fecha', today())
            ->where('estado', '!=', 'Cancelado')
            ->count();

        $ultimasOrdenes     = OrdenTrabajo::with(['vehiculo.cliente.usuario', 'estado'])->latest()->take(4)->get();
        $productosBajoStock = Inventario::with('producto')->whereRaw('cantidad <= stock_minimo')->take(4)->get();

        return view('admin.dashboard', compact(
            'totalOrdenes',
            'totalClientes',
            'bajosStock',
            'citasHoy',
            'ultimasOrdenes',
            'productosBajoStock'
        ));
    }
```

**Propósito**: Procesa lógica para la página dashboard  
**Ruta que lo ejecuta**: `GET /admin/dashboard`  
**Retorna**: Vista Blade con datos

**Desglose línea por línea:**

#### `public function index()`
- **`public`** → Accesible desde fuera de la clase
- **`function`** → Define un método
- **`index()`** → Nombre estándar para "listar/mostrar principal"
- **Sin parámetros** → No recibe argumentos

**Por qué `index()`:**
- Convención RESTful
- `index()` siempre muestra vista principal/resumen
- Se ejecuta cuando ruta es: `Route::get('/dashboard', [DashboardController::class, 'index'])`

#### Línea 13-16: Conteo de Órdenes Pendientes
```php
$totalOrdenes  = OrdenTrabajo::whereHas('estado', fn($q) =>
    $q->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
)->count();
```

**Propósito**: Contar órdenes que NO están terminadas/entregadas/canceladas

**Qué es `whereHas()`:**
```php
// whereHas('relacion', closure) → Filtrar por relación

// Equivalente SQL:
// SELECT COUNT(*) FROM ordenes_trabajo
// WHERE id_orden IN (
//   SELECT id_orden FROM ordenes_trabajo
//   JOIN estados_ot ON ...
//   WHERE estado_ot.nombre NOT IN ('Terminado', 'Entregado', 'Cancelado')
// )
```

**Desglose:**
- `OrdenTrabajo::` → Modelo
- `whereHas('estado', ...)` → Filtrar por relación `estado`
- `fn($q) => $q->whereNotIn(...)` → Arrow function (PHP 7.4+) filtra estados
- `'nombre', ['Terminado', 'Entregado', 'Cancelado']` → Excluye estos estados
- `->count()` → Cuenta registros

**Qué sucede si `estado` relación NO existe:**
- Error: "SQLSTATE[42S22]: Column not found" (estado no es columna)
- O error: "Relationship 'estado' not defined on model"

**Dependencia en Modelo:**
```php
// En app/Models/Tecnico/OrdenTrabajo.php
public function estado()
{
    return $this->belongsTo(EstadoOt::class, 'id_estado', 'id_estado');
}
```

**Qué sucede si se borra esta línea:**
- `$totalOrdenes` no existe
- Error en view: "Undefined variable $totalOrdenes"

**Cómo arreglarlo:**
- Restaurar línea
- O asignar valor por defecto: `$totalOrdenes = 0;`

#### Línea 17: Conteo de Clientes
```php
$totalClientes = Cliente::count();
```

**Propósito**: Contar total de clientes en sistema

**Desglose:**
- `Cliente::count()` → Ejecuta: `SELECT COUNT(*) FROM clientes;`
- Retorna número (int)

**Dependencias:**
- Tabla `clientes` existe en BD
- Modelo `Cliente` está importado

**Qué sucede si tabla NO existe:**
- Error: "SQLSTATE[42S02]: Table 'taller_laravel.clientes' doesn't exist"

**Cómo arreglarlo:**
- Ejecutar migraciones: `php artisan migrate`
- O crear tabla manualmente

#### Línea 18: Conteo de Productos Bajo Stock
```php
$bajosStock    = Inventario::whereRaw('cantidad <= stock_minimo')->count();
```

**Propósito**: Contar cuántos productos tienen cantidad ≤ stock mínimo

**Qué es `whereRaw()`:**
```php
// whereRaw permite escribir SQL directo

// Equivalente a:
// SELECT COUNT(*) FROM inventario
// WHERE cantidad <= stock_minimo;

// Nota: cantidad es columna, stock_minimo es columna (no valor)
```

**Desglose:**
- `Inventario::` → Modelo de tabla `inventario`
- `whereRaw('cantidad <= stock_minimo')` → Filtro SQL raw
- Compara dos columnas: cantidad vs stock_minimo
- `->count()` → Cuenta registros

**Ventajas de `whereRaw()`:**
- Permite comparar dos columnas
- Con `where()` normal: `where('cantidad', '<=', 100)` solo compara con valor fijo

**Desventajas:**
- Requiere conocimiento de SQL
- Vulnerable a SQL injection si usas variables sin parametrizar

**Forma segura con variables:**
```php
$minimo = 10;
whereRaw('cantidad <= ?', [$minimo]);  // ✓ Parametrizado
whereRaw('cantidad <= ' . $minimo);     // ❌ SQL injection
```

**Dependencias:**
- Tabla `inventario` con columnas: `cantidad`, `stock_minimo`
- Modelo `Inventario`

**Qué sucede si columnas NO existen:**
- Error: "SQLSTATE[42S22]: Column 'cantidad' not found"

#### Línea 19-21: Conteo de Citas para Hoy
```php
$citasHoy      = Cita::whereDate('fecha', today())
    ->where('estado', '!=', 'Cancelado')
    ->count();
```

**Propósito**: Contar citas programadas para hoy (excepto canceladas)

**Desglose:**
- `Cita::` → Modelo de tabla `citas`
- `whereDate('fecha', today())` → Filtra por fecha actual (ignora hora)
  - `'fecha'` → Columna timestamp/date
  - `today()` → Helper Laravel que retorna `Carbon::today()` (fecha actual)
  - Equivalente: `WHERE DATE(fecha) = '2026-09-24'`
- `->where('estado', '!=', 'Cancelado')` → Excluye estado Cancelado
- `->count()` → Cuenta registros

**Qué es `whereDate()`:**
```php
// Extrae solo la fecha de un timestamp
// Ignora la hora

// Equivalente SQL:
// WHERE DATE(fecha) = '2026-09-24'

// Sin whereDate sería:
// WHERE fecha >= '2026-09-24 00:00:00' AND fecha < '2026-09-25 00:00:00'
```

**Dependencias:**
- Tabla `citas` con columna `fecha` (timestamp/date)
- Modelo `Cita`

**Qué sucede si se borra:**
- `$citasHoy` no existe
- Error en view: "Undefined variable"

#### Línea 23: Últimas 4 Órdenes
```php
$ultimasOrdenes = OrdenTrabajo::with(['vehiculo.cliente.usuario', 'estado'])->latest()->take(4)->get();
```

**Propósito**: Obtener últimas 4 órdenes con información de vehículo, cliente, usuario y estado

**Desglose:**
- `OrdenTrabajo::` → Modelo
- `with(['vehiculo.cliente.usuario', 'estado'])` → Eager loading de relaciones
  - Evita N+1 queries
  - Carga `vehiculo`, y dentro vehículo carga `cliente`, y dentro cliente carga `usuario`
  - Carga `estado`
- `->latest()` → Ordena por `created_at DESC`
- `->take(4)` → Limita a 4 resultados
- `->get()` → Ejecuta query y retorna Collection

**Qué es Eager Loading (`with()`):**
```php
// SIN with (N+1 problem - MAL):
$ordenes = OrdenTrabajo::take(4)->get();           // Query 1
foreach ($ordenes as $orden) {
    $orden->vehiculo;        // Query 2, 3, 4, 5 (cada iteración)
    $orden->vehiculo->cliente;  // Más queries...
}
// Total: 1 + 4 + 4 + 4 + ... = Muchas queries

// CON with (Eager loading - BUENO):
$ordenes = OrdenTrabajo::with(['vehiculo.cliente', 'estado'])->take(4)->get();
// Total: 1 query + 2 queries (relaciones) = 3 queries
```

**Dependencias en Modelos:**
```php
// app/Models/Tecnico/OrdenTrabajo.php
public function vehiculo() {
    return $this->belongsTo(Vehiculo::class);
}
public function estado() {
    return $this->belongsTo(EstadoOt::class);
}

// app/Models/Admin/Vehiculo.php
public function cliente() {
    return $this->belongsTo(Cliente::class);
}

// app/Models/Cliente/Cliente.php
public function usuario() {
    return $this->belongsTo(Usuario::class);
}
```

**Qué sucede si relación NO existe:**
- Error: "Call to undefined method" o "SQLSTATE error"

**Qué sucede si se borra:**
- `$ultimasOrdenes` no existe
- Error en view

#### Línea 24: Productos Bajo Stock
```php
$productosBajoStock = Inventario::with('producto')->whereRaw('cantidad <= stock_minimo')->take(4)->get();
```

**Propósito**: Obtener 4 primeros productos bajo stock con detalle de producto

**Desglose:**
- `Inventario::` → Modelo
- `with('producto')` → Eager load relación `producto`
- `whereRaw('cantidad <= stock_minimo')` → Filtra bajo stock (igual que línea 18)
- `->take(4)` → Limita a 4
- `->get()` → Retorna Collection

**Dependencia en Modelo:**
```php
// app/Models/Admin/Inventario.php
public function producto() {
    return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
}
```

#### Línea 26-35: Retorno de Vista
```php
return view('admin.dashboard', compact(
    'totalOrdenes',
    'totalClientes',
    'bajosStock',
    'citasHoy',
    'ultimasOrdenes',
    'productosBajoStock'
));
```

**Propósito**: Renderiza vista Blade con variables

**Desglose:**

#### `view('admin.dashboard', ...)`
- **`view()`** → Helper Laravel
- **`'admin.dashboard'`** → Ruta de vista (carpeta.vista)
  - Ubicación: `resources/views/admin/dashboard.blade.php`
  - Puntos `.` mapean a barras `/`
- **Segundo argumento** → Array de variables para la vista

#### `compact(...)`
- **Qué es** → Helper que convierte variables a array
- **Equivalente**:
```php
compact('totalOrdenes', 'totalClientes', ...)
// Es igual a:
[
    'totalOrdenes' => $totalOrdenes,
    'totalClientes' => $totalClientes,
    ...
]
```

**Dependencias:**
- Vista `resources/views/admin/dashboard.blade.php` DEBE existir

**Qué sucede si vista NO existe:**
- Error: "View [admin.dashboard] not found"

**Qué sucede si se borra `return`:**
- Error fatal - no retorna nada
- Laravel espera response (view, redirect, JSON, etc.)

**Cómo arreglarlo:**
- Restaurar `return view(...)`
- O retornar otro tipo de response

---

## Resumen de Dependencias

### Modelos requeridos:
```
✓ app/Models/Admin/Inventario.php
✓ app/Models/Cliente/Cliente.php
✓ app/Models/Tecnico/Cita.php
✓ app/Models/Tecnico/OrdenTrabajo.php
```

### Tablas requeridas en BD:
```
✓ ordenes_trabajo (con id_estado FK)
✓ clientes
✓ inventario (con cantidad, stock_minimo)
✓ citas (con fecha, estado)
✓ estados_ot (con nombre)
```

### Vistas requeridas:
```
✓ resources/views/admin/dashboard.blade.php
```

### Variables pasadas a vista:
```php
$totalOrdenes        // int: cantidad órdenes pendientes
$totalClientes       // int: cantidad clientes
$bajosStock          // int: productos bajo stock
$citasHoy            // int: citas para hoy
$ultimasOrdenes      // Collection: últimas 4 órdenes (con relaciones)
$productosBajoStock  // Collection: 4 productos bajo stock
```

---

## Errores Comunes y Soluciones

### ❌ Error: "SQLSTATE[42S22]: Column not found"
```
Causa: Columna 'cantidad' o 'stock_minimo' no existe
Solución:
  php artisan migrate
  // Verificar en BD
  DESCRIBE inventario;
```

### ❌ Error: "View [admin.dashboard] not found"
```
Causa: Archivo Vista no existe
Solución:
  touch resources/views/admin/dashboard.blade.php
  // O crear con contenido
```

### ❌ Error: "Undefined variable $ultimasOrdenes"
```
Causa: Vista intenta usar variable que no existe
Solución:
  // En controlador, cambiar línea:
  $ultimasOrdenes = OrdenTrabajo::take(4)->get();
  // A:
  $ultimasOrdenes = OrdenTrabajo::with(['vehiculo.cliente.usuario', 'estado'])->latest()->take(4)->get();
```

### ❌ Error: "Relationship 'estado' not defined"
```
Causa: Modelo OrdenTrabajo no tiene método estado()
Solución:
  // En app/Models/Tecnico/OrdenTrabajo.php, agregar:
  public function estado() {
      return $this->belongsTo(EstadoOt::class, 'id_estado', 'id_estado');
  }
```

---

## Cómo Mantener Este Archivo

### Agregar nueva estadística:
```php
// En método index():
$nuevoMetrica = SomeModel::count();

// Agregar a compact():
return view('admin.dashboard', compact(
    'totalOrdenes',
    ...
    'nuevoMetrica'  // ← Agregar aquí
));
```

### Cambiar filtro de órdenes pendientes:
```php
// Cambiar línea 13-16:
$totalOrdenes = OrdenTrabajo::whereHas('estado', fn($q) =>
    $q->whereIn('nombre', ['En Progreso'])  // ← Cambiar condición
)->count();
```

### Cambiar número de registros mostrados:
```php
// Línea 23: cambiar take(4) a take(10)
$ultimasOrdenes = OrdenTrabajo::with([...])->latest()->take(10)->get();
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
