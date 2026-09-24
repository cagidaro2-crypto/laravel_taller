<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validar entrada
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required|min:6',
        ], [
            'correo.required'   => 'El correo es requerido.',
            'correo.email'      => 'El formato del correo no es válido.',
            'password.required' => 'La contraseña es requerida.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        Log::info('Login attempt', [
            'correo' => $request->correo,
            'ip' => $request->ip(),
        ]);

        // 2. Rate limiting - 5 intentos en 15 minutos
        $key = 'login_' . Str::lower($request->correo) . '_' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning('Rate limit excedido', ['correo' => $request->correo]);
            
            return back()->withErrors([
                'correo' => 'Demasiados intentos. Intenta en ' . ceil($seconds / 60) . ' minutos.',
            ])->withInput($request->only('correo'));
        }

        // 3. Buscar usuario
        $usuario = Usuario::where('correo', $request->correo)->first();

        // 4. Validar credenciales
        $decay = app()->isLocal() ? 60 : 900;
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            RateLimiter::hit($key, $decay);
            Log::warning('Invalid credentials', ['correo' => $request->correo]);
            
            return back()->withErrors([
                'correo' => 'Correo o contraseña incorrectos.',
            ])->withInput($request->only('correo'));
        }

        // 5. Verificar que esté activo
        if (!$usuario->activo) {
            Log::warning('Inactive user', ['usuario_id' => $usuario->id_usuario]);
            
            return back()->withErrors([
                'correo' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->withInput($request->only('correo'));
        }

        // 6. Cargar rol
        $usuario->load('rol');
        
        if (!$usuario->rol) {
            Log::error('User without role', ['usuario_id' => $usuario->id_usuario]);
            
            return back()->withErrors([
                'correo' => 'Error en la configuración de tu cuenta.',
            ])->withInput($request->only('correo'));
        }

        // 7. Login exitoso
        RateLimiter::clear($key);
        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        Log::info('Login successful', [
            'usuario_id' => $usuario->id_usuario,
            'correo' => $usuario->correo,
            'rol' => $usuario->rol->nombre_rol,
        ]);

        // 8. Redirigir según rol
        $dashboard = match ($usuario->rol->nombre_rol) {
            'Administrador' => 'admin.dashboard',
            'Técnico', 'Empleado' => 'tecnico.dashboard',
            'Cliente' => 'cliente.dashboard',
            default => '/',
        };

        return redirect()->route($dashboard)->with('success', '¡Bienvenido!');
    }

    public function logout(Request $request)
    {
        Log::info('Logout', ['usuario_id' => Auth::id()]);
        
        // Obtener usuario antes de logout (para logging)
        $usuarioId = Auth::id();
        
        // Logout y destruir sesión completamente
        Auth::logout();
        
        // Invalidar sesión
        $request->session()->invalidate();
        
        // Regenerar token CSRF
        $request->session()->regenerateToken();
        
        // Limpiar cookies de autenticación
        if (isset($_COOKIE['XSRF-TOKEN'])) {
            setcookie('XSRF-TOKEN', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['laravel_session'])) {
            setcookie('laravel_session', '', time() - 3600, '/');
        }

        Log::info('Logout completed', ['usuario_id' => $usuarioId]);
        
        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.')
            ->withoutCookie('XSRF-TOKEN')
            ->withoutCookie('laravel_session');
    }
}
