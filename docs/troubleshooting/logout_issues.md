# 🔓 Problemas de Logout (Cerrar Sesión) - Guía de Solución

## Problema: No puedo cerrar sesión

### Síntomas
- Click en "Cerrar sesión" no funciona
- Redirecciona a login pero vuelve a entrar automáticamente
- Sesión se mantiene activa aunque hayas hecho logout
- Browser cache o cookies no se limpian

---

## Causas Comunes

### 1. ❌ Sesión no se invalidada correctamente
**Código incorrecto:**
```php
public function logout(Request $request)
{
    Auth::logout();
    // Falta invalidar sesión
    return redirect()->route('login');
}
```

**Solución correcta:**
```php
public function logout(Request $request)
{
    // 1. Logout
    Auth::logout();
    
    // 2. Invalidar sesión
    $request->session()->invalidate();
    
    // 3. Regenerar token CSRF
    $request->session()->regenerateToken();
    
    // 4. Limpiar cookies manualmente
    setcookie('XSRF-TOKEN', '', time() - 3600, '/');
    setcookie('laravel_session', '', time() - 3600, '/');
    
    return redirect()->route('login');
}
```

### 2. ❌ CSRF token no regenerado
**Problema**: Token antiguo sigue siendo válido

**Solución:**
```php
$request->session()->regenerateToken();
```

### 3. ❌ Rutas mal configuradas
**Verificar en routes/web.php:**
```php
// ✓ CORRECTO - Acepta GET y POST
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// ❌ MAL - Solo acepta GET
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ❌ MAL - Formulario POST a ruta GET
// Formulario envía POST pero ruta solo GET
```

### 4. ❌ Formulario HTML mal estructurado
**Verificar en vista (resources/views/layouts/app.blade.php):**

```html
{{-- ✓ CORRECTO --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>

{{-- ❌ MAL - Falta @csrf --}}
<form method="POST" action="{{ route('logout') }}">
    <button type="submit">Cerrar sesión</button>
</form>

{{-- ❌ MAL - GET en lugar de POST --}}
<a href="{{ route('logout') }}">Cerrar sesión</a>
```

### 5. ❌ Middleware interceptando logout
**Verificar en app/Http/Middleware/CheckRole.php:**

```php
// ❌ MAL - Validar rol en logout
public function handle($request, Closure $next)
{
    if (! Auth::check() || Auth::user()->rol->nombre_rol !== $this->role) {
        abort(403);  // Bloquea logout si no hay rol
    }
    return $next($request);
}

// ✓ CORRECTO - Permitir logout sin restricción
if ($request->getPathInfo() === '/logout') {
    return $next($request);  // Dejar pasar logout
}
```

### 6. ❌ Browser caché/cookies
**Soluciones:**
```bash
# Opción 1: Limpiar caché manualmente
# Navegador → DevTools → Application → Storage → Clear All

# Opción 2: Desde código - Vaciar cookies
setcookie('XSRF-TOKEN', '', time() - 3600, '/');
setcookie('laravel_session', '', time() - 3600, '/');
setcookie('remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d', '', time() - 3600, '/');
```

---

## Solución Paso-a-Paso

### Paso 1: Verificar ruta
```php
// En routes/web.php
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
```

### Paso 2: Verificar controlador
```php
// En app/Http/Controllers/Auth/LoginController.php
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Limpiar cookies
    setcookie('XSRF-TOKEN', '', time() - 3600, '/');
    setcookie('laravel_session', '', time() - 3600, '/');
    
    return redirect()->route('login')->with('success', 'Sesión cerrada.');
}
```

### Paso 3: Verificar formulario en vista
```html
<!-- En resources/views/layouts/app.blade.php o dashboard.blade.php -->
<form method="POST" action="{{ route('logout') }}" style="margin:0;">
    @csrf
    <button type="submit" onclick="return confirm('¿Deseas cerrar sesión?')">
        🚪 Cerrar sesión
    </button>
</form>
```

### Paso 4: Permitir logout sin validación
```php
// En app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next)
{
    // Permitir logout sin validar rol
    if ($request->getPathInfo() === '/logout') {
        return $next($request);
    }
    
    // Validar rol para otras rutas
    if (! Auth::check() || Auth::user()->rol->nombre_rol !== $this->role) {
        abort(403);
    }
    
    return $next($request);
}
```

### Paso 5: Test de logout
```bash
# Opción 1: Test manual
1. Login con credenciales correctas
2. Ver que auth()->user() retorna objeto
3. Click "Cerrar sesión"
4. Verificar redirige a /login
5. Ir a /admin directamente → debe redirigir a login
6. Verificar cookies borradas (DevTools)

# Opción 2: Test con Tinker
php artisan tinker
>>> Auth::check()
# Debe retornar: false después de logout
```

---

## Código Correcto Completo

### LoginController.php - Método logout()
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // ... otros métodos ...

    public function logout(Request $request)
    {
        // Registrar logout
        Log::info('Logout iniciado', [
            'usuario_id' => Auth::id(),
            'correo' => Auth::user()?->correo,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);
        
        // 1. Obtener info antes de logout
        $usuarioId = Auth::id();
        
        // 2. Hacer logout
        Auth::logout();
        
        // 3. Invalidar sesión
        $request->session()->invalidate();
        
        // 4. Regenerar token CSRF
        $request->session()->regenerateToken();
        
        // 5. Limpiar cookies manualmente
        if (isset($_COOKIE['XSRF-TOKEN'])) {
            setcookie('XSRF-TOKEN', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['laravel_session'])) {
            setcookie('laravel_session', '', time() - 3600, '/');
        }
        
        // 6. Registrar logout exitoso
        Log::info('Logout completado', [
            'usuario_id' => $usuarioId,
            'timestamp' => now()
        ]);
        
        // 7. Redirigir
        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
```

### routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// ✓ CORRECTO - Acepta GET y POST
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
```

### Vista (resources/views/layouts/app.blade.php)
```html
<!-- Botón de logout -->
<form method="POST" action="{{ route('logout') }}" style="margin:0;">
    @csrf
    <button type="submit" 
            onclick="return confirm('¿Deseas cerrar sesión?')"
            class="btn btn-danger w-100">
        🚪 Cerrar sesión
    </button>
</form>
```

### Middleware CheckRole.php
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // ✓ PERMITIR LOGOUT SIN VALIDAR ROL
        if ($request->getPathInfo() === '/logout') {
            return $next($request);
        }
        
        // Validar autenticación
        if (!Auth::check()) {
            abort(401, 'No autenticado');
        }
        
        // Validar rol
        $userRole = Auth::user()->rol->nombre_rol ?? null;
        if (!in_array($userRole, $roles)) {
            abort(403, 'No tienes permiso para acceder');
        }
        
        return $next($request);
    }
}
```

---

## Verificación Rápida

Ejecuta este comando en terminal:

```bash
# Verificar que logout redirige correctamente
curl -X POST http://localhost:8000/logout -H "X-Requested-With: XMLHttpRequest" -v

# Debe retornar: 302 Redirect a /login
```

---

## Debuggear con Logs

### Ver qué está pasando en logout
```php
// En LoginController logout()
Log::info('LOGOUT DEBUG', [
    'auth_check_before' => Auth::check(),
    'user_id' => Auth::id(),
    'cookies' => $_COOKIE,
    'session_id' => session()->getId(),
]);

Auth::logout();

Log::info('LOGOUT DEBUG AFTER', [
    'auth_check_after' => Auth::check(),
    'session_id_after' => session()->getId(),
]);
```

### Ver logs
```bash
tail -f storage/logs/laravel.log | grep "LOGOUT DEBUG"
```

---

## Casos Especiales

### Cliente intenta acceder a /admin después de logout
**Debe redirigir a login:**
```php
// En middleware CheckRole
if (!Auth::check()) {
    return redirect()->route('login');
}
```

### Múltiples guardias (admin, web, api)
**Logout debe afectar todos:**
```php
public function logout(Request $request)
{
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login');
}
```

---

## Checklist de Solución

- [ ] Ruta acepta POST: `Route::match(['get', 'post'], '/logout', ...)`
- [ ] Controlador llama `Auth::logout()`
- [ ] Controlador llama `$request->session()->invalidate()`
- [ ] Controlador regenera token: `$request->session()->regenerateToken()`
- [ ] Formulario usa METHOD POST
- [ ] Formulario incluye @csrf
- [ ] Middleware permite logout (no valida rol en /logout)
- [ ] Cookies se limpian en headers
- [ ] Redirecciona a login correctamente
- [ ] Test: No puedes acceder a rutas protegidas después

---

**Última actualización**: Septiembre 2026  
**Versión**: 1.0.0  
**Mantenedor**: Sistema de Taller Latonería
