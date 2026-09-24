# 🐛 Errores Comunes - Guía de Solución Rápida

## Tabla de Contenidos
- [Errores de Instalación](#instalación)
- [Errores de Base de Datos](#base-de-datos)
- [Errores de Autenticación](#autenticación)
- [Errores de Rutas](#rutas)
- [Errores de Modelos/Relaciones](#modelosrelaciones)
- [Errores de Vistas](#vistas)
- [Errores de Permisos](#permisos)
- [Errores de Email](#email)
- [Errores de Inventario](#inventario)
- [Errores de Facturas](#facturas)

---

## Instalación

### ❌ Error: "PHP is not recognized"
```
Causa: PHP no está instalado o no está en PATH
Solución:
  1. Descargar PHP: https://www.php.net/downloads
  2. Instalar en: C:\php (o tu ruta)
  3. Agregar a PATH:
     - Sistema → Variables de entorno → PATH → Nueva → C:\php
  4. Verificar: php --version (debe mostrar versión)
```

### ❌ Error: "Composer is not installed"
```
Causa: Composer no está en PATH
Solución:
  1. Descargar: https://getcomposer.org/Composer-Setup.exe
  2. Instalar (agregará automático a PATH)
  3. Verificar: composer --version
  4. Reiniciar terminal/IDE
```

### ❌ Error: "Node.js not found"
```
Causa: Node.js no instalado
Solución:
  1. Descargar: https://nodejs.org/ (LTS 18+)
  2. Instalar
  3. Verificar: node --version
```

### ❌ Error: "npm ERR! code ERESOLVE"
```
Causa: Conflicto de versiones npm
Solución:
  npm install --legacy-peer-deps
  # O limpiar cache:
  npm cache clean --force
  npm install
```

---

## Base de Datos

### ❌ Error: "SQLSTATE[HY000]: General error: 1030"
```
Causa: BD no responde o no existe
Solución:
  1. Verificar MySQL está corriendo:
     - Windows: Services → MySQL → Start
  2. Verificar credenciales .env:
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=taller_laravel
     DB_USERNAME=root
     DB_PASSWORD=
  3. Probar conexión:
     mysql -h 127.0.0.1 -u root -p
     # Dejar password vacío si no tiene
```

### ❌ Error: "SQLSTATE[HY000]: Unknown database"
```
Causa: BD no existe
Solución:
  # En MySQL:
  CREATE DATABASE taller_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  # O desde Laravel:
  php artisan migrate
```

### ❌ Error: "SQLSTATE[42S02]: Table doesn't exist"
```
Causa: Migraciones no ejecutadas
Solución:
  php artisan migrate
  # Ver estado:
  php artisan migrate:status
```

### ❌ Error: "SQLSTATE[HY000]: Column not found"
```
Causa: Columna no existe (convención de nombres)
Solución:
  # Ej: Buscas 'email' pero la columna es 'correo'
  # En Model, declara:
  protected $table = 'usuarios';
  protected $primaryKey = 'id_usuario';
  
  # Luego:
  $usuario->correo;  // ✓ Funciona
```

### ❌ Error: "Integrity constraint violation: Foreign Key"
```
Causa: FK intenta insertar id_rol que no existe
Solución:
  # Verificar rol existe:
  SELECT * FROM roles WHERE id_rol = 1;
  # Si vacío, ejecutar seeder:
  php artisan db:seed --class=RoleSeeder
```

### ❌ Error: "Duplicate entry for key unique"
```
Causa: Correo ya existe en BD
Solución:
  # Verificar:
  SELECT * FROM usuarios WHERE correo = 'juan@mail.com';
  # Si existe, usar otro correo o eliminar:
  DELETE FROM usuarios WHERE correo = 'juan@mail.com';
```

---

## Autenticación

### ❌ Error: "SQLSTATE[42S22]: Unknown column 'email'"
```
Causa: Usuario model busca 'email', pero tabla usa 'correo'
Solución:
  # En app/Models/Admin/Usuario.php:
  public static function getAuthField(): string
  {
      return 'correo';  // ← Cambiar de 'email' a 'correo'
  }
```

### ❌ Error: "These credentials do not match our records"
```
Causa: Email/contraseña incorrectos
Solución:
  1. Verificar usuario existe:
     SELECT * FROM usuarios WHERE correo = 'juan@mail.com';
  2. Verificar contraseña:
     # Crear nueva con:
     php artisan tinker
     >>> $u = Usuario::find(1)
     >>> $u->password = Hash::make('nuevaPassword')
     >>> $u->save()
     >>> exit
  3. Intentar login nuevamente
```

### ❌ Error: "419 Page Expired"
```
Causa: CSRF token expiró o sesión perdida
Solución:
  1. Limpiar cookies/cache del navegador
  2. Reiniciar sesión
  3. Verificar: config/session.php
     SESSION_LIFETIME=120  (minutos)
  4. Si problema persiste:
     php artisan cache:clear
     php artisan config:clear
```

### ❌ Error: "Session store not set on request"
```
Causa: Middleware no registrado en Kernel.php
Solución:
  # En app/Http/Kernel.php, verificar:
  protected $middleware = [
      \App\Http\Middleware\StartSession::class,  // ← Debe existir
      // ...
  ];
```

---

## Rutas

### ❌ Error: "404 Not Found"
```
Causa: Ruta no definida
Solución:
  1. Verificar ruta existe:
     php artisan route:list | grep usuarios
  2. Verificar controlador existe:
     ls app/Http/Controllers/Admin/UsuarioController.php
  3. Verificar método en controlador:
     # En UsuarioController.php:
     public function index() { ... }  // ✓ Método debe existir
  4. Clear cache:
     php artisan route:cache
     php artisan route:clear
```

### ❌ Error: "MethodNotAllowedHttpException"
```
Causa: Método HTTP incorrecto (POST en ruta GET, etc.)
Solución:
  # En formulario, verificar method:
  <form method="POST" action="{{ route('admin.usuarios.store') }}">
      @csrf
      <!-- Campos -->
  </form>
  
  # En ruta:
  Route::post('usuarios', [UsuarioController::class, 'store']);
  # POST correcto ✓
```

### ❌ Error: "Class not found in routes"
```
Causa: Controlador no importado
Solución:
  # En routes/admin.php, top del archivo:
  use App\Http\Controllers\Admin\UsuarioController;  // ← Agregar
```

---

## Modelos/Relaciones

### ❌ Error: "Trying to get property of non-object"
```
Causa: Relación no cargada o no existe
Solución:
  # MAL (N+1 queries):
  $usuarios = Usuario::all();  // Sin with()
  foreach ($usuarios as $u) {
      echo $u->rol->nombre_rol;  // ❌ Error si no cargada
  }

  # BIEN (Eager loading):
  $usuarios = Usuario::with('rol')->all();
  foreach ($usuarios as $u) {
      echo $u->rol->nombre_rol;  // ✓ OK
  }
```

### ❌ Error: "Unknown column in 'where' clause"
```
Causa: Nombre de columna incorrecto
Solución:
  # MAL:
  Usuario::where('email', 'juan@mail.com')->first();
  # ❌ Columna 'email' no existe, es 'correo'

  # BIEN:
  Usuario::where('correo', 'juan@mail.com')->first();
  # ✓ OK
```

### ❌ Error: "Relationship not found"
```
Causa: Método de relación no definido en modelo
Solución:
  # En app/Models/Admin/Usuario.php, agregar:
  public function rol()
  {
      return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
  }

  # Luego puedes usar:
  $usuario->rol->nombre_rol;  // ✓ Funciona
```

### ❌ Error: "Trying to assign to non-existent model property"
```
Causa: Propiedad no está en $fillable
Solución:
  # En Model:
  protected $fillable = [
      'nombre',
      'correo',
      'password',  // ← Agregar aquí
      'id_rol',
      // ...
  ];

  # Luego:
  Usuario::create(['password' => 'test']);  // ✓ OK
```

---

## Vistas

### ❌ Error: "View not found"
```
Causa: Archivo .blade.php no existe
Solución:
  1. Crear archivo:
     touch resources/views/admin/usuarios/index.blade.php
  2. O verificar ruta en controlador:
     # En controller:
     return view('admin.usuarios.index');
     # Busca: resources/views/admin/usuarios/index.blade.php
```

### ❌ Error: "Undefined variable"
```
Causa: Variable no pasada a vista
Solución:
  # En controlador:
  return view('admin.usuarios.index', compact('usuarios'));
  #                                                         ↑
  # La variable 'usuarios' debe definirse antes de compact()

  # En vista:
  {{ $usuarios }}  // ✓ Accesible si pasada con compact()
```

### ❌ Error: "Call to undefined function route()"
```
Causa: Helper 'route' no disponible
Solución:
  # En vista, usar:
  <a href="{{ route('admin.usuarios.index') }}">
      # route() es helper de Laravel, debe estar disponible
  
  # Si aún falla, verificar config/app.php:
  'providers' => [
      // ...
      Illuminate\Routing\RoutingServiceProvider::class,  // ← Debe existir
  ]
```

---

## Permisos

### ❌ Error: "403 Forbidden"
```
Causa: Usuario no tiene rol requerido
Solución:
  # En BD, verificar usuario tiene rol correcto:
  SELECT id_usuario, id_rol FROM usuarios WHERE id_usuario = 5;
  # Debe retornar: 5, 1 (si Admin: rol 1)

  # O asignar rol:
  UPDATE usuarios SET id_rol = 1 WHERE id_usuario = 5;
  # 1 = Administrador
```

### ❌ Error: "Unauthorized" (401)
```
Causa: Usuario no autenticado
Solución:
  1. Verificar middleware en ruta:
     Route::get('/admin/...', [...])
         ->middleware(['auth', 'role:Administrador']);
     # Debe tener 'auth'
  
  2. Reiniciar sesión:
     - Logout → Login
     - Clear cookies/cache
```

### ❌ Error: "This action is unauthorized"
```
Causa: Usuario intenta acceder a recurso que no le pertenece
Solución:
  # Ej: Cliente intenta ver factura de otro cliente
  # En controlador, verificar:
  if ($factura->id_cliente !== auth()->user()->id_usuario) {
      abort(403, 'No autorizado');
  }
  # Permite acceso solo si es propietario
```

---

## Email

### ❌ Error: "Connection refused" en Mail
```
Causa: Servidor SMTP no configurado
Solución:
  # En .env:
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=tu-email@gmail.com
  MAIL_PASSWORD=tu-contraseña-app  (NO es tu password Gmail)
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=tu-email@gmail.com

  # Generar contraseña app:
  1. Gmail → Account → Security
  2. App passwords → Seleccionar Mail + Windows
  3. Copiar contraseña generada
  4. Pegar en MAIL_PASSWORD
```

### ❌ Error: "Swift_TransportException: Connection refused"
```
Causa: No hay conexión a servidor SMTP
Solución:
  1. Verificar datos SMTP en .env
  2. Testear conexión:
     php artisan tinker
     >>> Mail::raw('Test', function($m) { $m->to('tu@mail.com'); });
  3. Ver logs: storage/logs/laravel.log
```

### ❌ Error: "Mailable not found"
```
Causa: Clase Mail no existe
Solución:
  # Crear mailable:
  php artisan make:mail NotificacionEstadoVehiculoMail
  # Genera: app/Mail/NotificacionEstadoVehiculoMail.php
```

---

## Inventario

### ❌ Error: "No hay stock disponible"
```
Causa: Cantidad disponible < cantidad solicitada
Solución:
  # En BD:
  SELECT id_producto, cantidad, stock_minimo 
  FROM inventario 
  WHERE cantidad < 5;
  
  # Comprar más:
  UPDATE inventario SET cantidad = 100 WHERE id_producto = 5;
```

### ❌ Error: "Producto bajo stock"
```
Causa: Cantidad <= stock_minimo
Solución:
  # Comprar nuevo:
  UPDATE inventario 
  SET cantidad = cantidad + 50 
  WHERE id_producto = 5;

  # O cambiar mínimo:
  UPDATE inventario 
  SET stock_minimo = 5 
  WHERE id_producto = 5;
```

---

## Facturas

### ❌ Error: "Cannot generate factura, orden no terminada"
```
Causa: Orden no está en estado Terminado
Solución:
  # Verificar estado en BD:
  SELECT id_orden, id_estado FROM ordenes_trabajo WHERE id_orden = 5;
  # id_estado debe ser 3 (Terminado)

  # O actualizar:
  UPDATE ordenes_trabajo SET id_estado = 3 WHERE id_orden = 5;
```

### ❌ Error: "Duplicate entry for numero_factura"
```
Causa: Número de factura ya existe
Solución:
  # Asegurar generateNumeroFactura() es único:
  private function generarNumeroFactura() {
      $ano = date('Y');
      $count = Factura::whereYear('created_at', $ano)->count();
      return $ano . '-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
  }
```

### ❌ Error: "Cliente no puede ver esta factura"
```
Causa: Factura pertenece a otro cliente
Solución:
  # En controlador, validar:
  if ($factura->id_cliente !== auth()->user()->id_usuario) {
      abort(403);
  }
```

---

## Checklist Rápido

Cuando algo no funciona:

1. ☐ Ejecutar `php artisan migrate` (BD actualizada)
2. ☐ Ejecutar `php artisan cache:clear` (Cache limpiado)
3. ☐ Ver logs: `tail -f storage/logs/laravel.log`
4. ☐ Verificar `.env` (variables correctas)
5. ☐ Reiniciar servidor: `php artisan serve`
6. ☐ Limpiar cookies/cache navegador
7. ☐ Verificar syntax PHP: `php -l archivo.php`
8. ☐ Ver query SQL: `DB::enableQueryLog()` + `DB::getQueryLog()`

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
