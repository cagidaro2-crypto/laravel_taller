# 📄 app/Models/Admin/Usuario.php - Documentación Línea por Línea

## Resumen General
Modelo Eloquent que representa tabla `usuarios`. Extiende `Authenticatable` para soportar autenticación de Laravel. Contiene relaciones con roles, cliente, órdenes, citas, cotizaciones y ventas.

**Archivo**: `app/Models/Admin/Usuario.php`  
**Hereda de**: `Illuminate\Foundation\Auth\User` (clase base para autenticación)  
**Tabla BD**: `usuarios`  
**Clave primaria**: `id_usuario` (en lugar de `id` estándar)

**Dependencias:**
- Tabla `usuarios` en BD
- Tabla `roles` (relación)
- Tabla `clientes` (relación)
- Tabla `ordenes_trabajo` (relación)
- Tabla `cotizaciones` (relación)
- Tabla `citas` (relación)
- Tabla `ventas` (relación)

**Si se borra este archivo:**
- Error fatal: No existe modelo Usuario
- Todas las operaciones de usuarios fallan
- Autenticación no funciona

---

## Línea por Línea

### Línea 1-13: Imports
```php
<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Admin\Cotizacion;
use App\Models\Tecnico\Cita;
use App\Models\Admin\Venta;
```

**Propósito**: Declaración PHP, namespace, e importación de dependencias

**Desglose:**

#### Línea 4: Namespace
```php
namespace App\Models\Admin;
```
- Ubicación lógica: `App\Models\Admin`
- Ruta física: `app/Models/Admin/Usuario.php`

#### Línea 6-8: Tipos de Relaciones
```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
```

**Importaciones de relaciones (type hints):**
- `BelongsTo` → Usuario pertenece a un Rol (muchos-a-uno)
- `HasOne` → Usuario tiene UN Cliente (uno-a-uno)
- `HasMany` → Usuario tiene MUCHAS Órdenes/Citas (uno-a-muchos)

#### Línea 9: Clase Base de Autenticación
```php
use Illuminate\Foundation\Auth\User as Authenticatable;
```

**Propósito**: Importa clase base que soporta login

**Qué es `Authenticatable`:**
- Proporciona métodos para autenticación
- Implementa `AuthenticatableContract`
- Métodos: `getAuthIdentifier()`, `getAuthPassword()`, etc.

**Comparación:**
```php
// ❌ MAL - Solo hereda Model:
class Usuario extends Model { }
// No tiene métodos de auth

// ✓ BIEN - Hereda User (que extends Model + Auth):
class Usuario extends Authenticatable { }
// Tiene métodos de auth
```

#### Línea 10-13: Modelos Relacionados
```php
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Admin\Cotizacion;
use App\Models\Tecnico\Cita;
use App\Models\Admin\Venta;
```

- Modelos que se usan en relaciones
- Permite `return $this->hasMany(OrdenTrabajo::class, ...)`

---

### Línea 15-16: Declaración de Clase
```php
class Usuario extends Authenticatable
{
```

**Propósito**: Define modelo Usuario

**Hereda de**: `Authenticatable` (User de Laravel con auth)

**Diferencia:**
```php
// Model normal:
class Rol extends Model { }

// Model con autenticación:
class Usuario extends Authenticatable { }
```

---

### Línea 17-18: Nombre de Tabla
```php
    protected $table = 'usuarios';
```

**Propósito**: Especifica nombre de tabla en BD

**Convención Laravel:**
- Nombre de clase singular: `Usuario`
- Pluraliza automáticamente: `usuarios`
- **PERO**: Si está en namespace Admin, pluraliza a `usuarios` (sin prefijo `admin_`)

**Si no declaras `$table`:**
```php
// Laravel asume: users (por herencia de Authenticatable)
// ❌ MAL - buscaría tabla 'users', no 'usuarios'

// Solución: Declarar $table = 'usuarios'
```

**SQL resultante:**
```sql
SELECT * FROM usuarios;  -- Correcta
```

---

### Línea 20-21: Clave Primaria
```php
    protected $primaryKey = 'id_usuario';
```

**Propósito**: Especifica nombre de columna de clave primaria

**Convención Laravel:**
- Nombre estándar: `id`
- **Nuestro caso**: `id_usuario` (naming convention específica)

**Si no declaras:**
```php
// Laravel asume: id
// ❌ Buscaría columna 'id', no 'id_usuario'
// SQL: SELECT * FROM usuarios WHERE id = 5
// ❌ ERROR - columna 'id' no existe

// Solución: $primaryKey = 'id_usuario'
// SQL: SELECT * FROM usuarios WHERE id_usuario = 5
// ✓ CORRECTO
```

---

### Línea 23-30: Mass Assignment - Fillable
```php
    protected $fillable = [
        'id_rol',
        'nombre',
        'correo',
        'password',
        'telefono',
        'activo',
    ];
```

**Propósito**: Especifica qué campos se pueden asignar masivamente

**Qué es Mass Assignment:**
```php
// Mass assignment:
$usuario = Usuario::create($request->all());
// Asigna TODOS los campos del request de una vez

// Sin protección (GRAVE):
// Atacante envía: { admin: true, is_superuser: true }
// Se asignarían campos no esperados

// Solución: $fillable lista campos permitidos
// Solo estos se asignan: id_rol, nombre, correo, password, telefono, activo
```

**Campos listados:**
- `id_rol` → FK a tabla roles
- `nombre` → Nombre completo
- `correo` → Email único
- `password` → Contraseña (siempre hasheada)
- `telefono` → Teléfono opcional
- `activo` → Boolean (usuario activo/inactivo)

**Campos NO incluidos (protegidos):**
```
- id_usuario    → Auto-increment (genera BD)
- created_at    → Timestamp automático
- updated_at    → Timestamp automático
- remember_token → Token de "recuérdame"
```

**Alternativa a `$fillable`:**
```php
// Opción 1: $fillable (whitelist)
protected $fillable = ['nombre', 'correo', ...];

// Opción 2: $guarded (blacklist - MÁS PELIGROSO)
protected $guarded = ['admin'];  // ❌ Menos seguro

// Mejor: usar $fillable
```

---

### Línea 32-35: Hidden Fields
```php
    protected $hidden = [
        'password',
        'remember_token',
    ];
```

**Propósito**: Especifica campos que se ocultan al serializar (JSON/array)

**Cuándo se usa:**
```php
// Retornar usuario como JSON:
return response()->json($usuario);
// ✓ No incluye 'password' ni 'remember_token'

// Acceso directo sigue funcionando:
echo $usuario->password;  // ✓ Se puede acceder normalmente

// Pero en serialización se omite:
echo $usuario->toJson();
// { "id_usuario": 1, "nombre": "Juan", "correo": "juan@mail.com" }
// ❌ NO incluye password (correcto para seguridad)
```

**Por qué ocultar:**
- `password` → Nunca debe enviarse al frontend (es hash, pero no es información que deba viajar)
- `remember_token` → Token de autenticación interno (no debe exponerse)

---

### Línea 37-39: Type Casts
```php
    protected $casts = [
        'activo' => 'boolean',
    ];
```

**Propósito**: Convertir valores de BD a tipos PHP específicos

**Cómo funciona:**
```php
// En BD: activo (TINYINT, 0 o 1)
// Sin cast:
$usuario->activo;  // "1" (string)
if ($usuario->activo) { ... }  // ✓ Funciona igual

// Con cast a boolean:
$usuario->activo;  // true (boolean)
if ($usuario->activo) { ... }  // ✓ Más limpio y type-safe
```

**Tipos de cast disponibles:**
```php
protected $casts = [
    'activo'       => 'boolean',
    'cantidad'     => 'integer',
    'precio'       => 'decimal:2',
    'datos'        => 'json',  // STRING JSON ↔ ARRAY
    'fecha'        => 'datetime',
];
```

**Beneficios:**
- Type safety
- Conversión automática
- Documentación del tipo esperado

---

### Línea 41-43: Auth Identifier
```php
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }
```

**Propósito**: Especifica columna que identifica al usuario en autenticación

**Qué es:**
- Laravel necesita saber qué columna es la clave de usuario
- Estándar: `id`
- Nuestro caso: `id_usuario`

**Uso interno:**
```php
// Laravel internamente hace:
$authId = auth()->user()->getAuthIdentifierName();
// Retorna: 'id_usuario'

// Se usa para:
Session::put('auth_id', $user->id_usuario);
```

**Si no lo declares:**
```php
// Laravel asume: 'id'
// ❌ Buscará columna 'id' en sesión
// Error: Columna no existe
```

---

### Línea 47-50: Get Auth Password
```php
    /**
     * Laravel necesita este campo para Auth::attempt con 'email'.
     * Aquí redirigimos 'email' => 'correo'.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }
```

**Propósito**: Retorna el password para verificación en login

**Qué es:**
- Método de `Authenticatable`
- Laravel lo llama cuando hace `Auth::attempt()`
- Retorna campo de contraseña

**Uso:**
```php
// En LoginController:
Auth::attempt(['correo' => $request->correo, 'password' => $request->password]);

// Laravel internamente:
1. Busca usuario por correo
2. Obtiene password: $user->getAuthPassword() // → $this->password
3. Verifica: Hash::check($input_password, $db_password)
4. Si coincide: login
```

**Si la contraseña estuviera en otra columna:**
```php
public function getAuthPassword(): string
{
    return $this->hash_contrasena;  // Otro nombre de columna
}
```

---

### Línea 53-56: Get Auth Field
```php
    /**
     * Nombre del campo que actúa como "username" para Auth.
     */
    public static function getAuthField(): string
    {
        return 'correo';
    }
```

**Propósito**: Especifica qué campo se usa como "username" en login

**Qué es:**
- Campo único para identificar al usuario (email, username, etc.)
- Nuestro caso: `correo` (email)
- Estándar: `email`

**Uso:**
```php
// En LoginController:
'correo' => 'required|email'  // Usar correo en lugar de email

// Laravel internamente lo usa para:
Auth::attempt(['correo' => $request->correo, 'password' => $request->password])
```

**¿Por qué custom?**
- Tabla `usuarios` usa `correo` en lugar de `email` estándar
- Si no lo declararas, Laravel buscaría `email`

---

### Línea 58-61: Relación Rol (BelongsTo)
```php
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
```

**Propósito**: Relación - Usuario pertenece a UN Rol

**Tipo de relación**: Many-to-One (muchos usuarios → un rol)

**Estructura:**
```
usuarios (muchos)
├── Usuario A → Rol "Administrador"
├── Usuario B → Rol "Técnico"
├── Usuario C → Rol "Cliente"
└── Usuario D → Rol "Técnico"
```

**Desglose:**

#### `public function rol(): BelongsTo`
- **`rol()`** → Nombre de la relación
- **`: BelongsTo`** → Type hint (retorna BelongsTo)
- Permite: `$usuario->rol`

#### `belongsTo(Rol::class, 'id_rol', 'id_rol')`
- **`Rol::class`** → Modelo relacionado
- **`'id_rol'`** → FK en tabla `usuarios`
- **`'id_rol'`** → PK en tabla `roles`

**Sintaxis:**
```php
belongsTo(ModeloRelacionado::class, FOREIGN_KEY, PRIMARY_KEY_REMOTA)
```

**Acceso en código:**
```php
$usuario = Usuario::find(1);
echo $usuario->rol->nombre_rol;  // "Administrador"
```

**SQL generado:**
```sql
SELECT * FROM usuarios WHERE id_usuario = 1;
-- Retorna: { id_usuario: 1, id_rol: 3, ... }

SELECT * FROM roles WHERE id_rol = 3;
-- Retorna: { id_rol: 3, nombre_rol: "Administrador" }
```

---

### Línea 63-66: Relación Cliente (HasOne)
```php
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
    }
```

**Propósito**: Relación - Usuario tiene UN Cliente

**Tipo de relación**: One-to-One (usuario → cliente)

**Estructura:**
```
Usuario "Juan" (id=5)
  ↓
  Cliente { id_usuario: 5, documento: "123456", direccion: "..." }
```

**Diferencia vs BelongsTo:**
```
belongsTo:  Usuario → Rol (usuario "pertenece a" rol)
hasOne:     Usuario → Cliente (usuario "tiene" cliente)
```

**Acceso en código:**
```php
$usuario = Usuario::find(5);
$cliente = $usuario->cliente;  // Objeto Cliente
echo $cliente->documento;
```

**Por qué hasOne (no hasMany):**
- Usuario puede tener solo 1 registro Cliente
- Si es Técnico/Admin: $usuario->cliente = null

---

### Línea 68-71: Relación Órdenes (HasMany)
```php
    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_usuario', 'id_usuario');
    }
```

**Propósito**: Relación - Usuario tiene MUCHAS Órdenes de Trabajo

**Tipo de relación**: One-to-Many (usuario → órdenes múltiples)

**Estructura:**
```
Usuario "Técnico Juan" (id=2)
  ├─ Orden 1 { id_orden: 10, id_usuario: 2, ... }
  ├─ Orden 2 { id_orden: 11, id_usuario: 2, ... }
  └─ Orden 3 { id_orden: 12, id_usuario: 2, ... }
```

**Acceso en código:**
```php
$tecnico = Usuario::find(2);
$ordenes = $tecnico->ordenesTrabajo;  // Collection

foreach ($ordenes as $orden) {
    echo $orden->id_orden;
}
```

**SQL generado:**
```sql
SELECT * FROM ordenes_trabajo WHERE id_usuario = 2;
```

---

### Línea 73-76: Relación Cotizaciones (HasMany)
```php
    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_usuario', 'id_usuario');
    }
```

**Propósito**: Usuario (Admin/Técnico) crea muchas cotizaciones

**Estructura:** Same as `ordenesTrabajo()` → Many

---

### Línea 78-81: Relación Citas (HasMany)
```php
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'id_usuario', 'id_usuario');
    }
```

**Propósito**: Usuario crea/asigna muchas citas

---

### Línea 83-86: Relación Ventas (HasMany)
```php
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_usuario', 'id_usuario');
    }
```

**Propósito**: Usuario crea muchas ventas

---

## Resumen de Relaciones

```
Usuario
├── rol() → BelongsTo Rol (Many-to-One)
├── cliente() → HasOne Cliente (One-to-One, solo si es Cliente)
├── ordenesTrabajo() → HasMany OrdenTrabajo
├── cotizaciones() → HasMany Cotizacion
├── citas() → HasMany Cita
└── ventas() → HasMany Venta
```

---

## Ejemplos de Uso

### Crear Usuario
```php
$usuario = Usuario::create([
    'id_rol' => 1,
    'nombre' => 'Juan Pérez',
    'correo' => 'juan@mail.com',
    'password' => Hash::make('password123'),
    'telefono' => '123456789',
    'activo' => true,
]);
```

### Obtener Usuario con Relaciones
```php
$usuario = Usuario::with(['rol', 'cliente', 'ordenesTrabajo'])
    ->find(1);

echo $usuario->rol->nombre_rol;        // "Administrador"
echo $usuario->cliente->documento;     // "123456"
echo $usuario->ordenesTrabajo->count(); // 5
```

### Buscar Usuarios Activos
```php
$usuariosActivos = Usuario::where('activo', true)->get();
```

### Login
```php
if (Auth::attempt(['correo' => $request->correo, 'password' => $request->password])) {
    auth()->user();  // Objeto Usuario actual
}
```

---

## Errores Comunes

### ❌ Error: "Undefined property $password"
```
Causa: Campo password no está en $fillable
Solución: Agregar 'password' a $fillable
```

### ❌ Error: "Trying to get property 'nombre_rol' of non-object"
```
Causa: No cargó relación rol()
Solución:
  $usuario = Usuario::with('rol')->find(1);
  echo $usuario->rol->nombre_rol;  // ✓ Funciona
```

### ❌ Error: "Unknown database 'usuarios'"
```
Causa: BD no existe o tabla no se migró
Solución:
  php artisan migrate
```

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
