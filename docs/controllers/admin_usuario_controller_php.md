# 📄 app/Http/Controllers/Admin/UsuarioController.php - Documentación Línea por Línea

## Resumen General
Controlador REST que gestiona el CRUD completo de usuarios del sistema (Administrador, Técnico, Cliente). Valida datos, hashea contraseñas, y crea registros de cliente cuando es necesario.

**Archivo**: `app/Http/Controllers/Admin/UsuarioController.php`  
**Responsabilidad**: Gestionar usuarios (crear, leer, actualizar, eliminar)  
**Rutas asociadas**: 
```
GET    /admin/usuarios              → index()
GET    /admin/usuarios/create       → create()
POST   /admin/usuarios              → store()
GET    /admin/usuarios/{usuario}    → show()
GET    /admin/usuarios/{usuario}/edit → edit()
PUT    /admin/usuarios/{usuario}    → update()
DELETE /admin/usuarios/{usuario}    → destroy()
```

**Dependencias:**
- `App\Http\Controllers\Controller`
- `App\Models\Admin\Rol`
- `App\Models\Admin\Usuario`
- `Illuminate\Http\Request`
- `Illuminate\Support\Facades\Hash` (hashear contraseñas)
- `Illuminate\Support\Facades\DB` (transacciones)
- Tabla `usuarios`, `roles`, `clientes` en BD

**Si se borra este archivo:**
- Error 500 al acceder a `/admin/usuarios`
- Panel de gestión de usuarios no funciona
- No se pueden crear/editar/eliminar usuarios

---

## Línea por Línea

### Línea 1-10: Imports
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Rol;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
```

**Propósito**: Declaración PHP, namespace, e importación de dependencias

**Desglose:**

#### Línea 1-2: Apertura PHP
- Estándar en todo archivo PHP

#### Línea 4: Namespace
```php
namespace App\Http\Controllers\Admin;
```
- Ubica esta clase en namespace `App\Http\Controllers\Admin`
- Ruta física: `app/Http/Controllers/Admin/`
- PSR-4 autoloading: Composer carga automáticamente

#### Línea 6: Controller Base
```php
use App\Http\Controllers\Controller;
```
- Importa clase controladora base
- Proporciona métodos shared para todos los controladores

#### Línea 7-8: Modelos
```php
use App\Models\Admin\Rol;
use App\Models\Admin\Usuario;
```
- `Rol` → Modelo para tabla `roles` (Administrador, Técnico, Cliente)
- `Usuario` → Modelo para tabla `usuarios` (login, datos personales)

**Ubicación:**
- `app/Models/Admin/Rol.php`
- `app/Models/Admin/Usuario.php`

#### Línea 9: Request
```php
use Illuminate\Http\Request;
```
- Objeto que contiene datos de petición HTTP
- Acceso a formularios: `$request->nombre`, `$request->all()`, etc.

#### Línea 10-11: Facades
```php
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
```

**Hash Facade:**
- `Hash::make()` → Hashea contraseña con bcrypt
- Importante: Almacena SIEMPRE hasheada, nunca en texto plano

**DB Facade:**
- `DB::transaction()` → Ejecuta código dentro de transacción BD
- Atomicidad: Si algo falla, rollback automático

---

### Línea 12: Declaración de Clase
```php
class UsuarioController extends Controller
{
```

**Propósito**: Define clase controladora  
**Hereda de**: `Controller` (clase base)

---

### Línea 13-29: Método index()
```php
    public function index(Request $request)
    {
        $query = Usuario::with('rol');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('correo', 'like', '%' . $request->buscar . '%');
        }

        $usuarios = $query->paginate(15);
        $roles    = Rol::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }
```

**Propósito**: Listar usuarios con búsqueda y paginación

**Ruta**: `GET /admin/usuarios`

**Desglose línea por línea:**

#### Línea 14: Query Base
```php
$query = Usuario::with('rol');
```
- `Usuario::` → Inicia query
- `with('rol')` → Eager loading: carga rol de cada usuario en 1 query (no N+1)
- `$query` → Objeto query sin ejecutar aún (builder pattern)

**Sin eager loading (MALO - N+1):**
```php
$usuarios = Usuario::take(15)->get();  // Query 1
foreach ($usuarios as $u) {
    echo $u->rol->nombre_rol;          // Query 2, 3, 4... (N queries)
}
```

**Con eager loading (BUENO):**
```php
$usuarios = Usuario::with('rol')->paginate(15);  // 2 queries
foreach ($usuarios as $u) {
    echo $u->rol->nombre_rol;  // Ya en memoria, sin query adicional
}
```

#### Línea 16-18: Búsqueda Condicional
```php
if ($request->filled('buscar')) {
    $query->where('nombre', 'like', '%' . $request->buscar . '%')
          ->orWhere('correo', 'like', '%' . $request->buscar . '%');
}
```

**Propósito**: Si usuario ingresa texto en formulario de búsqueda, filtrar

**Desglose:**

#### `$request->filled('buscar')`
- **Qué hace**: Valida que parámetro 'buscar' existe y no está vacío
- **Equivalente**: `!empty($request->input('buscar'))`
- **Uso**: Evita queries innecesarios si no hay búsqueda

#### `$query->where('nombre', 'like', '%' . $request->buscar . '%')`
- **Método**: WHERE con LIKE (búsqueda parcial)
- **Sintaxis**: `where(columna, operador, valor)`
- **`like`** → Búsqueda de texto (SQL: `LIKE`)
- **`%` + texto + `%`** → Comodines (cualquier carácter antes/después)
- **Ejemplo**: Buscar "juan" matchea "Juan Carlos", "Juanjo", etc.

**SQL generado:**
```sql
SELECT * FROM usuarios WHERE nombre LIKE '%juan%'
```

#### `->orWhere('correo', 'like', '%' . $request->buscar . '%')`
- **`orWhere`** → OR lógico (no AND)
- **Resultado**: Busca en nombre O correo
- **Ejemplo**: Usuario busca "juan" → Matchea nombre="Juan" O correo="juan@mail.com"

**SQL generado:**
```sql
WHERE nombre LIKE '%juan%' OR correo LIKE '%juan%'
```

**Vulnerabilidad evitada (SQL Injection):**
```php
// ❌ MALO - vulnerable:
$query->where('nombre', 'like', '%' . $request->buscar . '%');
// Si buscar = "'; DROP TABLE usuarios; --"
// SQL: WHERE nombre LIKE '%'; DROP TABLE usuarios; --%'
// Desastre total

// ✓ BUENO - Laravel parametriza automáticamente:
$query->where('nombre', 'like', '%' . $request->buscar . '%');
// Laravel escapa caracteres especiales internamente
```

#### Línea 20: Paginación
```php
$usuarios = $query->paginate(15);
```
- **`paginate(15)`** → Divide resultados en páginas de 15 registros
- **Qué retorna**: `Paginator` object con:
  - `->items()` o `->all()` → Registros de página actual
  - `->links()` → HTML de botones "anterior/siguiente"
  - `->total()` → Total de registros
- **URL**: Parámetro `?page=2` en URL para página 2

#### Línea 21: Cargar Roles
```php
$roles = Rol::all();
```
- Carga TODOS los roles (Administrador, Técnico, Cliente)
- Usado en formularios (dropdown para asignar rol)

#### Línea 23: Retornar Vista
```php
return view('admin.usuarios.index', compact('usuarios', 'roles'));
```
- **Vista**: `resources/views/admin/usuarios/index.blade.php`
- **Datos pasados**:
  - `$usuarios` → Paginator con usuarios de página actual
  - `$roles` → Array/Collection de roles

---

### Línea 25-30: Método create()
```php
    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }
```

**Propósito**: Mostrar formulario para crear nuevo usuario

**Ruta**: `GET /admin/usuarios/create`

**Desglose:**
- `Rol::all()` → Carga roles para dropdown
- `view('admin.usuarios.create', ...)` → Renderiza formulario vacío
- Vista debe contener: `<form method="POST" action="{{ route('admin.usuarios.store') }}">`

---

### Línea 32-66: Método store()
```php
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'correo'    => 'required|email|max:150|unique:usuarios,correo',
            'id_rol'    => 'required|exists:roles,id_rol',
            'telefono'  => 'nullable|string|max:20',
            'password'  => 'required|min:8|confirmed',
            'documento' => 'nullable|string|max:30',
        ], [
            'nombre.required'  => 'Debe completar todos los campos obligatorios antes de continuar.',
            'correo.unique'    => 'Ya existe un usuario registrado con este número de identificación.',
            'id_rol.required'  => 'Debe completar todos los campos obligatorios antes de continuar.',
            'password.required'=> 'Debe completar todos los campos obligatorios antes de continuar.',
        ]);

        $usuario = DB::transaction(function () use ($request) {
            $rol = Rol::find($request->id_rol);

            $usuario = Usuario::create([
                'id_rol'   => $request->id_rol,
                'nombre'   => $request->nombre,
                'correo'   => $request->correo,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'activo'   => true,
            ]);

            if ($rol && $rol->nombre_rol === 'Cliente') {
                \App\Models\Cliente\Cliente::create([
                    'id_usuario' => $usuario->id_usuario,
                    'documento'  => $request->documento ?: ('CLI-' . str_pad($usuario->id_usuario, 6, '0', STR_PAD_LEFT)),
                    'direccion'  => $request->direccion ?: 'No especificada',
                ]);
            }

            return $usuario;
        });

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario registrado exitosamente.');
    }
```

**Propósito**: Procesar formulario de creación de usuario

**Ruta**: `POST /admin/usuarios`

**Desglose:**

#### Línea 33-43: Validación de Datos
```php
$request->validate([
    'nombre'    => 'required|string|max:100',
    'correo'    => 'required|email|max:150|unique:usuarios,correo',
    'id_rol'    => 'required|exists:roles,id_rol',
    'telefono'  => 'nullable|string|max:20',
    'password'  => 'required|min:8|confirmed',
    'documento' => 'nullable|string|max:30',
], [
    // Mensajes de error personalizados
]);
```

**Propósito**: Validar datos del formulario

**Desglose de reglas:**

##### `'nombre' => 'required|string|max:100'`
- `required` → No puede estar vacío
- `string` → Debe ser texto
- `max:100` → Máximo 100 caracteres
- **SQL**: `nombre VARCHAR(100) NOT NULL`

##### `'correo' => 'required|email|max:150|unique:usuarios,correo'`
- `required` → Obligatorio
- `email` → Debe ser formato email válido
- `max:150` → Máximo 150 caracteres
- `unique:usuarios,correo` → NO PUEDE existir en tabla usuarios, columna correo
  - **Qué hace**: Valida que correo no esté ya registrado
  - **SQL**: `SELECT COUNT(*) FROM usuarios WHERE correo = 'nuevo@mail.com'` → Debe ser 0

##### `'id_rol' => 'required|exists:roles,id_rol'`
- `required` → Debe seleccionar un rol
- `exists:roles,id_rol` → El id_rol DEBE existir en tabla roles
  - **Qué hace**: Validación de referencia
  - **SQL**: `SELECT COUNT(*) FROM roles WHERE id_rol = 5` → Debe ser 1

##### `'telefono' => 'nullable|string|max:20'`
- `nullable` → Puede estar vacío (opcional)
- `string` → Si se completa, debe ser texto
- `max:20` → Máximo 20 caracteres

##### `'password' => 'required|min:8|confirmed'`
- `required` → Obligatorio
- `min:8` → Mínimo 8 caracteres
- `confirmed` → Debe coincidir con campo `password_confirmation`
  - **Forma**: `<input name="password_confirmation">`
  - **Seguridad**: Usuario debe escribir contraseña dos veces correctamente

##### `'documento' => 'nullable|string|max:30'`
- Opcional, pero si se ingresa máximo 30 caracteres

**Mensajes de error personalizados (línea 42-45):**
```php
[
    'nombre.required'  => 'Debe completar todos los campos...',
    'correo.unique'    => 'Ya existe un usuario registrado...',
    // ...
]
```
- Si validación falla, se usan estos mensajes en lugar de mensajes por defecto
- Sintaxis: `'campo.regla' => 'mensaje'`

**Qué sucede si validación falla:**
- Redirect a formulario anterior (GET /admin/usuarios/create)
- Mensajes de error en sesión flash
- Datos del formulario se repoblación automáticamente (old())

#### Línea 47-65: Transacción BD
```php
$usuario = DB::transaction(function () use ($request) {
    $rol = Rol::find($request->id_rol);

    $usuario = Usuario::create([
        'id_rol'   => $request->id_rol,
        'nombre'   => $request->nombre,
        'correo'   => $request->correo,
        'password' => Hash::make($request->password),
        'telefono' => $request->telefono,
        'activo'   => true,
    ]);

    if ($rol && $rol->nombre_rol === 'Cliente') {
        \App\Models\Cliente\Cliente::create([
            'id_usuario' => $usuario->id_usuario,
            'documento'  => $request->documento ?: ('CLI-' . str_pad($usuario->id_usuario, 6, '0', STR_PAD_LEFT)),
            'direccion'  => $request->direccion ?: 'No especificada',
        ]);
    }

    return $usuario;
});
```

**Propósito**: Ejecutar múltiples operaciones BD de forma atómica

**¿Por qué transacción?**
- Si es Cliente: crear AMBOS registros Usuario + Cliente
- Si falla Cliente::create(), Usuario quedaría huérfano en BD
- Transacción: Si algo falla, rollback AMBOS

**Desglose:**

##### `DB::transaction(function () use ($request) { ... })`
- `DB::transaction()` → Inicia transacción BD
- `function () { ... }` → Código a ejecutar
- `use ($request)` → Permite acceso a $request dentro del closure

**Qué es `use` en PHP:**
```php
// Sin use:
$x = 5;
$func = function () {
    echo $x;  // ❌ Error: $x no existe en scope
};

// Con use:
$x = 5;
$func = function () use ($x) {
    echo $x;  // ✓ $x = 5
};
```

##### Línea 48: Cargar Rol
```php
$rol = Rol::find($request->id_rol);
```
- Busca registro de rol por ID
- Retorna objeto Rol o null si no existe

##### Línea 50-56: Crear Usuario
```php
$usuario = Usuario::create([
    'id_rol'   => $request->id_rol,
    'nombre'   => $request->nombre,
    'correo'   => $request->correo,
    'password' => Hash::make($request->password),
    'telefono' => $request->telefono,
    'activo'   => true,
]);
```

**Desglose:**

###### `Usuario::create([...])`
- `create()` → Método para crear e insertar registro
- Retorna objeto Usuario creado (con id_usuario autoincrement asignado)

###### `'password' => Hash::make($request->password)`
- **Hash::make()** → Hashea contraseña con bcrypt
- **Algoritmo**: bcrypt (One-way, no se puede desencriptar)
- **Verificación**: Se usa `Hash::check()` al hacer login
- **Costo**: bcrypt es lento deliberadamente (seguridad)

**Ejemplo completo:**
```php
$password = "MiContraseña123";
$hashed = Hash::make($password);
// $hashed = "$2y$10$nOUIs5kJ7naTuTFkHK1He.4kF7DWj0o9VHWstE34sKyU26zIUU.Ey"

// Al verificar (login):
if (Hash::check("MiContraseña123", $hashed)) {
    // ✓ Coincide
}
```

###### `'activo' => true`
- Nuevo usuario está activo por defecto
- Puede ser desactivado luego (soft delete)

##### Línea 58-64: Crear Cliente si es Necesario
```php
if ($rol && $rol->nombre_rol === 'Cliente') {
    \App\Models\Cliente\Cliente::create([
        'id_usuario' => $usuario->id_usuario,
        'documento'  => $request->documento ?: ('CLI-' . str_pad($usuario->id_usuario, 6, '0', STR_PAD_LEFT)),
        'direccion'  => $request->direccion ?: 'No especificada',
    ]);
}
```

**Propósito**: Si usuario es Cliente, crear también registro en tabla clientes

**Desglose:**

###### `if ($rol && $rol->nombre_rol === 'Cliente')`
- `$rol &&` → Verifica que rol existe (no null)
- `$rol->nombre_rol === 'Cliente'` → Solo si es cliente exactamente
- **Comparación estricta (`===`)**: Compara tipo y valor

###### Crear Cliente
```php
\App\Models\Cliente\Cliente::create([
    'id_usuario' => $usuario->id_usuario,
    'documento'  => ...,
    'direccion'  => ...,
]);
```
- `\App\Models\Cliente\Cliente::` → Ruta completa del modelo (evita conflictos de namespace)
- `id_usuario` → FK a tabla usuarios (relación)
- `documento` → ID único del cliente (cédula, RUC, etc.)

###### `$request->documento ?: ('CLI-' . str_pad(...))`
- **Operador ternario (`?:`)**: Asigna valor si parámetro está vacío
- Si formulario tiene documento: úsalo
- Si está vacío: genera automático "CLI-000005" (CLI- + id_usuario con 6 dígitos)

**Ejemplo:**
```
$usuario->id_usuario = 5
$documento auto = 'CLI-' . str_pad(5, 6, '0', STR_PAD_LEFT)
            = 'CLI-' . '000005'
            = 'CLI-000005'
```

###### `'direccion' => $request->direccion ?: 'No especificada'`
- Si no hay dirección en formulario, asigna valor por defecto

##### Línea 65: Retorno de Transacción
```php
return $usuario;
```
- Retorna objeto Usuario creado
- Cierra transacción (commit automático si todo success)

**Si hay error dentro del transaction:**
```php
// Ejemplo: Validación de Cliente falla
\App\Models\Cliente\Cliente::create([...]);  // ❌ Error

// Resultado: ROLLBACK AUTOMÁTICO
// - Usuario NO se crea
// - Cliente NO se crea
// - BD queda consistente (no Usuario huérfano)
```

#### Línea 67-69: Redirect con Mensaje
```php
return redirect()->route('admin.usuarios.index')
    ->with('success', 'Usuario registrado exitosamente.');
```
- Redirect a listado de usuarios
- Flash message (aparece una sola vez en vista siguiente)
- En vista: `@if(session('success')) {{ session('success') }} @endif`

---

### Línea 71-76: Método show()
```php
    public function show(Usuario $usuario)
    {
        $usuario->load('rol', 'cliente');
        return view('admin.usuarios.show', compact('usuario'));
    }
```

**Propósito**: Mostrar detalle de usuario

**Ruta**: `GET /admin/usuarios/{usuario}`

**Desglose:**

#### Route Model Binding
```php
public function show(Usuario $usuario)
```
- Laravel inyecta automáticamente objeto Usuario
- Parámetro URL `{usuario}` mapea a columna `id_usuario`
- Si no existe: error 404 automático

**Equivalente antiguo (MALO):**
```php
public function show($id) {
    $usuario = Usuario::find($id);
    if (!$usuario) abort(404);
    ...
}
```

#### `$usuario->load('rol', 'cliente')`
- **`load()`** → Eager loading (después del fetch)
- Carga relaciones `rol` y `cliente`
- **Nota**: Si ya cargadas via `with()`, no hace queries adicionales (smart)

#### `compact('usuario')`
- Pasacargo objeto Usuario a vista

---

### Línea 78-83: Método edit()
```php
    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }
```

**Propósito**: Mostrar formulario de edición

**Ruta**: `GET /admin/usuarios/{usuario}/edit`

**Desglose:**
- Route model binding inyecta Usuario
- Carga roles para dropdown
- Vista debe rellenar campos con datos actuales (old())

---

### Línea 85-104: Método update()
```php
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:150|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario',
            'id_rol'   => 'required|exists:roles,id_rol',
            'telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'Los datos ingresados contienen errores. Por favor corrija los campos señalados.',
            'correo.email'    => 'Los datos ingresados contienen errores. Por favor corrija los campos señalados.',
        ]);

        $usuario->update([
            'id_rol'   => $request->id_rol,
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'telefono' => $request->telefono,
            'activo'   => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Información actualizada correctamente.');
    }
```

**Propósito**: Procesar actualización de usuario

**Ruta**: `PUT /admin/usuarios/{usuario}`

**Diferencias vs store():**

#### Validación de correo
```php
'correo' => 'required|email|max:150|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario'
```
- **unique** normalmente rechazaría correo si ya existe (incluso el actual)
- **Excepto el usuario actual**: `unique:usuarios,correo,{id_usuario},id_usuario`
- Sintaxis: `unique:tabla,columna,exceptoId,columnaId`

**Ejemplo:**
```
Usuario actual: id=5, correo="juan@mail.com"
Valida: ¿"juan@mail.com" existe en usuarios (excepto id=5)?
- Juan cambia correo a "juan.lopez@mail.com" → ✓ Valida
- Juan deja "juan@mail.com" → ✓ Valida (excepción)
- Juan intenta cambiar a correo que ya existe (id=3) → ❌ Rechaza
```

#### Sin `password` en update
- No se cambia contraseña (solo datos básicos)
- Si usuario necesita cambiar contraseña: ruta separada

#### `$request->boolean('activo', true)`
- Valor por defecto: true (si no se envía, activo=true)
- Checkbox HTML no envía valor si no está checkeado

**HTML:**
```html
<input type="checkbox" name="activo" value="1">
<!-- Si NO checkeado: no se envía en request
     Si SÍ checkeado: $request->activo = "1" o "on"
-->

<!-- Solución con boolean() helper: -->
$request->boolean('activo', true)  // ✓ Retorna true/false
```

#### `$usuario->update([...])`
- Actualiza SOLO campos especificados (mass assignment)
- Requiere `$fillable` en modelo (protección contra asignación masiva)

---

### Línea 106-122: Método destroy()
```php
    public function destroy(Usuario $usuario)
    {
        // Verificar registros activos (TDLP-003 escenario 8)
        $tieneActivos = $usuario->ordenesTrabajo()->exists()
            || $usuario->citas()->exists()
            || $usuario->cotizaciones()->exists();

        if ($tieneActivos) {
            return back()->with('error', 'No es posible eliminar este usuario porque tiene registros activos asociados. Finalice o reasigne los registros antes de continuar.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
```

**Propósito**: Eliminar usuario (con validaciones)

**Ruta**: `DELETE /admin/usuarios/{usuario}`

**Desglose:**

#### Validación: Verificar Registros Activos
```php
$tieneActivos = $usuario->ordenesTrabajo()->exists()
    || $usuario->citas()->exists()
    || $usuario->cotizaciones()->exists();
```

**Propósito**: Evitar eliminar usuario si tiene órdenes/citas/cotizaciones

**Desglose:**

##### `$usuario->ordenesTrabajo()->exists()`
- **`ordenesTrabajo()`** → Relación (hasMany)
- **`->exists()`** → Valida si hay al menos 1 registro
- **Retorna**: true/false (no trae datos, solo verifica)
- **Mejor que**: `count() > 0` (existe() es más eficiente)

**SQL:**
```sql
SELECT EXISTS(
    SELECT 1 FROM ordenes_trabajo WHERE id_usuario = 5
)
```

##### `||` (OR)
- Usuario NO se puede eliminar si tiene:
  - órdenes_trabajo OR
  - citas OR
  - cotizaciones

#### Validación Falla
```php
if ($tieneActivos) {
    return back()->with('error', '...');
}
```
- `back()` → Redirect a página anterior
- `.with('error', ...)` → Flash message error
- Usuario NO se elimina

#### Validación Pasa: Eliminar
```php
$usuario->delete();
```
- Ejecuta DELETE en BD
- **Nota**: Puede ser soft delete si modelo usa `SoftDeletes`

#### Redirect con Éxito
```php
return redirect()->route('admin.usuarios.index')
    ->with('success', 'Usuario eliminado correctamente.');
```

---

## Resumen de Dependencias

### Modelos requeridos:
```
✓ app/Models/Admin/Usuario.php
✓ app/Models/Admin/Rol.php
✓ app/Models/Cliente/Cliente.php (para crear cliente si es necesario)
```

### Tablas requeridas:
```
✓ usuarios (id_usuario, nombre, correo, password, id_rol, activo, ...)
✓ roles (id_rol, nombre_rol)
✓ clientes (id_usuario FK, documento, direccion)
```

### Vistas requeridas:
```
✓ resources/views/admin/usuarios/index.blade.php
✓ resources/views/admin/usuarios/create.blade.php
✓ resources/views/admin/usuarios/edit.blade.php
✓ resources/views/admin/usuarios/show.blade.php
```

### Relaciones esperadas en Usuario modelo:
```
public function rol() { return $this->belongsTo(Rol::class, 'id_rol', 'id_rol'); }
public function cliente() { return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario'); }
public function ordenesTrabajo() { return $this->hasMany(OrdenTrabajo::class, 'id_usuario', 'id_usuario'); }
public function citas() { return $this->hasMany(Cita::class, 'id_usuario', 'id_usuario'); }
public function cotizaciones() { return $this->hasMany(Cotizacion::class, 'id_usuario', 'id_usuario'); }
```

---

## Errores Comunes

### ❌ Error: "unique_usuarios_correo"
```
Causa: Correo ya existe en BD
Solución:
  // En validación update:
  'unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario'
  // Permite correo actual (excepción)
```

### ❌ Error: "password_confirmation"
```
Causa: Formulario no tiene campo password_confirmation
Solución:
  <input type="password" name="password">
  <input type="password" name="password_confirmation">
  <!-- Deben coincidir para pasar validación -->
```

### ❌ Error: "No es posible eliminar..."
```
Causa: Usuario tiene órdenes/citas activas
Solución:
  // Primero finalizar/reasignar registros
  // O crear relación con cascadeOnDelete()
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
