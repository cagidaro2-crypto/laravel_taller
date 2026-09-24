# 🔧 Guía Avanzada de Resolución de Problemas

## Metodología de Debug

### Paso 1: Reproducir el Error
```
1. Notar EXACTAMENTE qué hizo (pasos)
2. Qué esperaba que pasara
3. Qué pasó en cambio
4. Mensaje de error EXACTO (copiar/pegar)
5. Cuando empezó a fallar (hoy, ayer, siempre)
```

### Paso 2: Investigar Logs
```bash
# Ver últimos logs:
tail -f storage/logs/laravel.log

# Si nada aquí, revisar:
# - Archivo php.ini (error_reporting)
# - MySQL error log
# - Nginx/Apache error log
```

### Paso 3: Usar Tinker para Testing
```bash
# Entrar a REPL PHP interactivo:
php artisan tinker

# Testear queries:
>>> $usuario = Usuario::find(1)
>>> $usuario->correo
>>> $usuario->rol

# Testear transacciones:
>>> DB::transaction(function() { /* código */ });

# Ver query log:
>>> DB::enableQueryLog();
>>> Usuario::all();
>>> DB::getQueryLog();

# Salir:
>>> exit
```

### Paso 4: Verificar Configuración
```bash
# Ver valores .env cargados:
php artisan config:show

# Regenerar config cache:
php artisan config:cache
php artisan config:clear

# Ver rutas:
php artisan route:list
php artisan route:list | grep usuarios
```

---

## Problemas Frecuentes por Componente

### 🔐 Autenticación

#### Problema: Login no funciona aunque credenciales son correctas
**Diagnóstico:**
```bash
# 1. Verificar usuario existe:
php artisan tinker
>>> Usuario::where('correo', 'juan@mail.com')->first()

# 2. Verificar contraseña:
>>> $u = Usuario::find(1)
>>> Hash::check('password123', $u->password)
# Retorna: true si OK, false si mal
```

**Soluciones comunes:**
- Contraseña no fue hasheada (guardar con `Hash::make()`)
- Campo 'correo' vs 'email' (custom en Sistema)
- Usuario inactivo (activo=0)

#### Problema: Sesión expira demasiado rápido
**Diagnóstico:**
```bash
# Ver SESSION_LIFETIME en .env:
php artisan config:show | grep SESSION

# Valor en minutos (default: 120)
```

**Soluciones:**
```env
# En .env:
SESSION_LIFETIME=480  # 8 horas
# O en config/session.php:
'lifetime' => env('SESSION_LIFETIME', 120),
```

---

### 🗄️ Base de Datos

#### Problema: Migraciones no funcionan
**Diagnóstico:**
```bash
php artisan migrate:status
# Muestra qué migraciones se ejecutaron

# Ver migración específica:
php artisan migrate --step=1
# Rollback último paso:
php artisan migrate:rollback --step=1
```

**Soluciones:**
```bash
# Resetear BD completamente (⚠️ BORRA DATOS):
php artisan migrate:reset
php artisan migrate

# O rollback individual:
php artisan migrate:rollback --target=migracion_name
```

#### Problema: "Table already exists" en migración
**Causa:** Migración intenta crear tabla que ya existe

**Solución:**
```php
// En migración, usar:
if (!Schema::hasTable('usuarios')) {
    Schema::create('usuarios', function (Blueprint $table) {
        // ...
    });
}
```

#### Problema: FK constraint error al insertar
**Diagnóstico:**
```sql
-- Verificar FK existe:
SELECT * FROM roles WHERE id_rol = 1;

-- Ver FK definidas:
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'roles';
```

**Soluciones:**
```bash
# 1. Ejecutar seeder:
php artisan db:seed

# 2. O insertar manualmente:
php artisan tinker
>>> Rol::create(['nombre_rol' => 'Administrador'])
```

---

### 🎛️ Controladores

#### Problema: Método no encontrado en controlador
**Error típico:** `BadMethodCallException: Call to undefined method`

**Diagnóstico:**
```bash
# Verificar método existe:
grep -n "public function index" app/Http/Controllers/Admin/UsuarioController.php

# Si no existe, agregar
```

**Solución rápida:**
```php
// Agregar método faltante en controlador:
public function index()
{
    // Implementar lógica
    return view('vista');
}
```

#### Problema: Route model binding no funciona
**Error típico:** "No query results found" con parámetro de ruta

**Diagnóstico:**
```php
// En ruta:
Route::get('usuarios/{usuario}', [UsuarioController::class, 'show']);

// En controlador:
public function show(Usuario $usuario)
{
    // $usuario inyectado automáticamente por ID
}

// Si error, verificar:
// 1. ¿PrimaryKey es 'id_usuario'? (no 'id')
// 2. ¿URL param es 'usuario' singular?
```

**Soluciones:**
```php
// Opción 1: Declarar primaryKey en modelo:
class Usuario extends Authenticatable {
    protected $primaryKey = 'id_usuario';
}

// Opción 2: Route model binding customizado:
Route::get('usuarios/{usuario}', [...])
    ->where('usuario', '[0-9]+');
```

---

### 🎨 Vistas

#### Problema: Blade variable no se renderiza
**Síntoma:** Ver `{{ $variable }}` en HTML renderizado (no reemplazado)

**Causas y soluciones:**
```blade
{{-- MAL - Variable no escaped (seguridad): --}}
{!! $contenido_html !!}

{{-- BIEN - Variable escapada: --}}
{{ $variable }}

{{-- MAL - Variable en directives: --}}
@if $usuario->activo
@endif

{{-- BIEN - Directiva Blade: --}}
@if ($usuario->activo)
@endif

{{-- MAL - Acceder propiedad sin isset: --}}
{{ $usuario->no_existe }}  {{-- Error si undefined --}}

{{-- BIEN - Con ?? (null coalescing): --}}
{{ $usuario->no_existe ?? 'N/A' }}
```

#### Problema: Componentes/includes no funcionan
**Diagnóstico:**
```blade
{{-- MAL - Ruta incorrecta: --}}
@include('admin/usuarios/form')  {{-- / no son puntos --}}

{{-- BIEN - Usar puntos para rutas: --}}
@include('admin.usuarios.form')

{{-- Busca: resources/views/admin/usuarios/form.blade.php --}}
```

---

### 📧 Email

#### Problema: Emails no se envían
**Diagnóstico:**
```bash
# 1. Ver si están en queue:
php artisan queue:work

# 2. Verificar SMTP:
php artisan config:show | grep MAIL

# 3. Test manual:
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('tu@mail.com'));
```

**Soluciones:**
```env
# En .env, verificar:
MAIL_MAILER=log  # Para testing (ver en storage/logs)
MAIL_MAILER=smtp # Para producción
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_FROM_ADDRESS=tu-email@gmail.com
```

#### Problema: "Connection refused" en SMTP
**Causa:** Credenciales incorrectas o servidor no accesible

**Verificar:**
```bash
# Testear conexión:
telnet smtp.gmail.com 587
# Si no conecta → problema de red/firewall

# Verificar contraseña app (no Gmail password):
# Gmail → Account → Security → App passwords
```

---

### 📦 Inventario & Consumo

#### Problema: Cantidad negativa en inventario
**Causa:** Técnico registra más consumo que disponible

**Diagnóstico:**
```sql
SELECT id_producto, cantidad, stock_minimo 
FROM inventario 
WHERE cantidad < 0;
```

**Solución:**
```php
// En ConsumoMaterialController, validar:
$validado = $request->validate([
    'cantidad' => 'required|numeric|min:1|max:' . $inventario->cantidad
]);
// max: no puede ser más que disponible
```

#### Problema: Auditoría de inventario inconsistente
**Causa:** Sin transacción, consumo se registra pero inventario no se actualiza

**Solución:**
```php
DB::transaction(function () {
    // 1. Crear consumo
    ConsumoMaterial::create([...]);
    
    // 2. Actualizar inventario
    $inventario->decrement('cantidad', $cantidad);
    
    // 3. Registrar auditoría
    AuditoriaInventario::create([...]);
    
    // Si algo falla aquí, TODO rollback
});
```

---

### 💰 Facturas & Pagos

#### Problema: Factura no se genera aunque orden está terminada
**Diagnóstico:**
```bash
php artisan tinker
>>> $orden = OrdenTrabajo::find(5)
>>> $orden->estado->nombre
# Debe retornar 'Terminado' exactamente
```

**Soluciones:**
```php
// En generarFactura(), validar:
if ($ordene->estado->nombre !== 'Terminado') {
    abort(400, 'Orden debe estar Terminada');
}

// O más flexible:
$estadosPermitidos = ['Terminado', 'Listo'];
if (!in_array($ordene->estado->nombre, $estadosPermitidos)) {
    abort(400, ...);
}
```

#### Problema: Monto total incorrecto en factura
**Diagnóstico:**
```sql
-- Ver cálculo:
SELECT 
    (SELECT SUM(precio) FROM servicios s 
     JOIN orden_servicios os ON s.id_servicio = os.id_servicio 
     WHERE os.id_orden = 5) as servicios,
    (SELECT SUM(cantidad * precio_venta) FROM consumo_materiales cm 
     JOIN productos p ON cm.id_producto = p.id_producto 
     WHERE cm.id_orden = 5) as productos;

-- Compara con factura:
SELECT subtotal, impuesto, total FROM facturas WHERE id_orden = 5;
```

**Soluciones:**
```php
// En cálculo, usar Decimal para exactitud:
$subtotal = $orden->servicios->sum('precio')
          + $orden->consumoMateriales->sum(function($cm) {
              return $cm->cantidad * $cm->producto->precio_venta;
          });

// NO usar floats, usar Decimal:
$impuesto = $subtotal * 0.13;
// Mejor:
$impuesto = bcmul($subtotal, 0.13, 2);
```

---

## Herramientas de Debug

### Laravel Debugbar (Recomendado para desarrollo)
```bash
# Instalar:
composer require barryvdh/laravel-debugbar --dev

# Usar:
php artisan serve
# Barra de debug aparece en esquina inferior derecha
# Muestra:
# - Queries ejecutadas
# - Tiempo de ejecución
# - Variables de sesión
# - Rutas
```

### XDebug (Para stepping)
```bash
# En php.ini agregar:
[XDebug]
zend_extension = xdebug.so
xdebug.mode = debug
xdebug.start_with_request = yes

# En IDE (PhpStorm, VSCode), configurar escucha
# Luego breakpoints funcionan normalmente
```

### Query Log
```php
// En controller/test:
DB::enableQueryLog();

$usuarios = Usuario::all();
$ordenes = OrdenTrabajo::all();

// Ver queries ejecutadas:
dd(DB::getQueryLog());
// Output:
// array:2 [
//   0 => array [ 
//      "query" => "SELECT * FROM usuarios",
//      "time" => 0.25
//   ]
//   ...
// ]
```

---

## Performance - Cuando es lento

### Diagnóstico
```bash
# 1. Ver tiempo request:
# En DevTools navegador → Network → XHR
# Ver columna "Time"

# 2. Medir en código:
$start = microtime(true);
// Código a medir
$end = microtime(true);
echo ($end - $start) * 1000; // ms
```

### Problemas Comunes

#### N+1 Query Problem
```php
// MAL - LENTO:
$usuarios = Usuario::all();  // 1 query
foreach ($usuarios as $u) {
    echo $u->rol->nombre;     // +15 queries
}
// Total: 16 queries

// BIEN - RÁPIDO:
$usuarios = Usuario::with('rol')->all();  // 2 queries
foreach ($usuarios as $u) {
    echo $u->rol->nombre;  // Sin queries adicionales
}
```

#### Carga Excesiva de Datos
```php
// MAL - Carga TODO de tabla grande:
$productos = Producto::all();
// Si hay 10,000 productos, carga todo en memoria

// BIEN - Paginar:
$productos = Producto::paginate(50);
```

#### Query Sin Índice
```sql
-- LENTO (busca 100,000 registros):
SELECT * FROM ordenes_trabajo WHERE descripcion LIKE '%Toyota%';

-- RÁPIDO (con índice):
SELECT * FROM ordenes_trabajo WHERE id_estado = 2;
-- Si existe índice en id_estado
```

**Solución: Agregar índices en migración:**
```php
Schema::table('ordenes_trabajo', function (Blueprint $table) {
    $table->index('id_estado');
    $table->index('id_usuario');
    $table->fulltext('descripcion');  // Para búsqueda de texto
});
```

---

## Backup & Recovery

### Backup de BD
```bash
# Dumpar BD a archivo:
mysqldump -u root -p taller_laravel > backup_2026_09_24.sql

# Restaurar desde backup:
mysql -u root -p taller_laravel < backup_2026_09_24.sql
```

### Backup de Código
```bash
# Git:
git add .
git commit -m "Backup antes de cambios importantes"
git push

# O tar:
tar -czf taller_backup_2026_09_24.tar.gz /ruta/proyecto/
```

### Recuperar Código
```bash
# Ver cambios recientes:
git log --oneline -10

# Revert a commit anterior:
git revert COMMIT_ID

# O restore archivo:
git restore archivo.php
```

---

## Checklist Pre-Deploy

- [ ] BD migrada: `php artisan migrate`
- [ ] Seeders ejecutados: `php artisan db:seed`
- [ ] Cache limpio: `php artisan cache:clear`
- [ ] Config limpio: `php artisan config:clear`
- [ ] Routes cacheado: `php artisan route:cache`
- [ ] Assets compilado: `npm run build`
- [ ] .env correcto (producción)
- [ ] Logs no tienen errores: `tail storage/logs/laravel.log`
- [ ] Prueba login funciona
- [ ] Prueba crear orden funciona
- [ ] Prueba generar factura funciona
- [ ] Prueba email se envía
- [ ] Backup de BD hecho

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
