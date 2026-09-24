# 📄 app/Models/Admin/Rol.php - Documentación Línea por Línea

## Resumen General
Modelo Eloquent simple que representa tabla `roles`. Define los 3 roles del sistema: Administrador, Técnico y Cliente.

**Archivo**: `app/Models/Admin/Rol.php`  
**Hereda de**: `Illuminate\Database\Eloquent\Model`  
**Tabla BD**: `roles`  
**Clave primaria**: `id_rol` (custom, no `id`)

**Dependencias:**
- Tabla `roles` en BD
- Tabla `usuarios` (relación inversa)

**Si se borra este archivo:**
- Error: "Class not found" cuando intenta usar Rol
- UsuarioController no funciona
- Asignación de roles falla

---

## Línea por Línea

### Línea 1-6: Imports
```php
<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
```

**Propósito**: Apertura PHP, namespace e importaciones

**Desglose:**

#### Línea 1-2
- Estándar PHP

#### Línea 4
```php
namespace App\Models\Admin;
```
- Ubica modelo en `App\Models\Admin`
- Ruta física: `app/Models/Admin/Rol.php`

#### Línea 6-7: Imports de Eloquent
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
```
- `Model` → Clase base de todos los modelos
- `HasMany` → Type hint para relación uno-a-muchos

---

### Línea 8: Declaración de Clase
```php
class Rol extends Model
{
```

**Propósito**: Define modelo Rol

**Hereda de**: `Model` (clase base Eloquent)

---

### Línea 9-10: Nombre de Tabla
```php
    protected $table = 'roles';
```

**Propósito**: Especifica nombre de tabla en BD

**Convención Laravel:**
- Nombre de clase: `Rol` (singular)
- Pluraliza automáticamente: `roles`
- Coincide con tabla real: `roles`

**En este caso**: Coincide perfectamente, pero se declara por claridad

**SQL resultante:**
```sql
SELECT * FROM roles;
```

---

### Línea 12-13: Clave Primaria
```php
    protected $primaryKey = 'id_rol';
```

**Propósito**: Especifica columna de clave primaria

**Convención Laravel:**
- Estándar: `id`
- Nuestro caso: `id_rol` (custom naming)

**Si no se declara:**
```php
// Laravel asume: id
// ❌ SELECT * FROM roles WHERE id = 1
// Error: Columna 'id' no existe

// Con $primaryKey:
// ✓ SELECT * FROM roles WHERE id_rol = 1
```

---

### Línea 15-17: Mass Assignment - Fillable
```php
    protected $fillable = [
        'nombre_rol',
    ];
```

**Propósito**: Especifica campos asignables masivamente

**Campo permitido:**
- `nombre_rol` → Nombre del rol ("Administrador", "Técnico", "Cliente")

**Campos protegidos (auto-generados):**
- `id_rol` → Auto-increment
- `created_at` → Timestamp automático
- `updated_at` → Timestamp automático

**Uso:**
```php
// ✓ Permitido (está en $fillable)
$rol = Rol::create(['nombre_rol' => 'Gerente']);

// ❌ No permitido (no está en $fillable)
Rol::create(['id_rol' => 999, 'nombre_rol' => 'Admin']);
// id_rol se ignora
```

---

### Línea 18-21: Relación Usuarios (HasMany)
```php
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }
```

**Propósito**: Relación inversa - Rol tiene muchos Usuarios

**Tipo de relación**: One-to-Many (rol → usuarios múltiples)

**Estructura:**
```
Rol "Administrador" (id_rol=1)
  ├─ Usuario 1 { id_usuario: 1, id_rol: 1, ... }
  ├─ Usuario 2 { id_usuario: 2, id_rol: 1, ... }
  └─ Usuario 3 { id_usuario: 3, id_rol: 1, ... }
```

**Desglose:**

#### `public function usuarios(): HasMany`
- **`usuarios()`** → Nombre de relación
- **`: HasMany`** → Type hint (retorna HasMany)

#### `hasMany(Usuario::class, 'id_rol', 'id_rol')`
- **`Usuario::class`** → Modelo relacionado
- **`'id_rol'`** → FK en tabla usuarios
- **`'id_rol'`** → PK en tabla roles

**Acceso en código:**
```php
$rol = Rol::find(1);  // Rol "Administrador"
$usuarios = $rol->usuarios;  // Collection de 3 usuarios

foreach ($usuarios as $usuario) {
    echo $usuario->nombre;
}
```

**SQL generado:**
```sql
SELECT * FROM roles WHERE id_rol = 1;
-- Retorna: { id_rol: 1, nombre_rol: "Administrador" }

SELECT * FROM usuarios WHERE id_rol = 1;
-- Retorna: 3 usuarios con id_rol=1
```

---

## Relaciones Completas

### Relación Bidireccional

**Usuario.php:**
```php
public function rol(): BelongsTo {
    return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
}
```

**Rol.php:**
```php
public function usuarios(): HasMany {
    return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
}
```

**Patrón:**
- Usuario → Rol: `belongsTo()` (muchos a uno)
- Rol → Usuario: `hasMany()` (uno a muchos)

---

## Tablas y Datos

### Estructura tabla `roles`
```sql
CREATE TABLE roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    nombre_rol VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Datos iniciales (Seeder)
```php
class RoleSeeder extends Seeder {
    public function run() {
        Rol::create(['nombre_rol' => 'Administrador']);
        Rol::create(['nombre_rol' => 'Técnico']);
        Rol::create(['nombre_rol' => 'Cliente']);
    }
}
```

### Valores en BD
```
id_rol | nombre_rol
-------|---------------
   1   | Administrador
   2   | Técnico
   3   | Cliente
```

---

## Ejemplos de Uso

### Obtener rol por ID
```php
$rol = Rol::find(1);
echo $rol->nombre_rol;  // "Administrador"
```

### Obtener todos los roles
```php
$roles = Rol::all();  // Collection de 3 roles

foreach ($roles as $rol) {
    echo $rol->nombre_rol . "\n";
    // Administrador
    // Técnico
    // Cliente
}
```

### Obtener usuarios de un rol
```php
$rol = Rol::find(1);  // Administrador
$usuarios = $rol->usuarios;  // Collection de usuarios admin

foreach ($usuarios as $usuario) {
    echo $usuario->nombre;
}
```

### Crear nuevo rol
```php
$rol = Rol::create(['nombre_rol' => 'Gerente']);
echo $rol->id_rol;  // 4 (auto-increment)
```

### Validar rol de usuario
```php
$usuario = Usuario::find(1);
if ($usuario->rol->nombre_rol === 'Administrador') {
    echo "Tiene acceso admin";
}
```

---

## Errores Comunes

### ❌ Error: "Unknown column 'id' in 'where clause'"
```
Causa: No declaró $primaryKey = 'id_rol'
Solución: Agregar en modelo:
protected $primaryKey = 'id_rol';
```

### ❌ Error: "Trying to get property 'nombre_rol' of non-object"
```
Causa: Rol no existe o relación no está cargada
Solución:
$usuario = Usuario::with('rol')->find(1);
echo $usuario->rol->nombre_rol;  // ✓ Correcto
```

### ❌ Error: "Integrity constraint violation" al crear usuario
```
Causa: id_rol no existe en tabla roles
Solución:
1. Verificar que roles existen:
   SELECT * FROM roles;
2. Si está vacía, ejecutar seeder:
   php artisan db:seed --class=RoleSeeder
```

---

## Mantenimiento

### Agregar nuevo rol
```php
// Manual en seeder:
Rol::create(['nombre_rol' => 'Supervisor']);

// O manual en BD:
INSERT INTO roles (nombre_rol) VALUES ('Supervisor');
```

### Cambiar nombre de rol
```php
$rol = Rol::find(1);
$rol->update(['nombre_rol' => 'Super Administrador']);
```

### Listar cuántos usuarios por rol
```php
$rol = Rol::find(1);
echo $rol->usuarios->count();  // 5 usuarios
```

---

## Consideraciones de Diseño

### ¿Por qué 3 roles fijos?
- Sistema simple sin permisos granulares
- Suficiente para taller: Admin (gestión), Técnico (trabajo), Cliente (servicios)
- Fácil de entender y mantener

### ¿Alternativa: Permisos granulares?
```php
// ❌ OVERCOMPLICATED para este proyecto:
Usuario → Rol → Permisos
Usuario → Permisos (direct)

// ✓ SIMPLE Y SUFICIENTE:
Usuario → Rol (Administrador, Técnico, Cliente)
// Middleware CheckRole valida automáticamente
```

### ¿Qué si necesitas más roles?
1. Agregar en seeder/migración
2. Crear nuevas rutas con `middleware: ['role:NuevoRol']`
3. Crear nuevo layout y vistas para ese rol
4. Crear controladores específicos

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
