<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required',
        ], [
            'correo.required'   => 'Los campos marcados son obligatorios.',
            'correo.email'      => 'Ingrese un correo electrónico válido.',
            'password.required' => 'Los campos marcados son obligatorios.',
        ]);

        // RF-04: Bloqueo por 5 intentos fallidos
        $key = 'login.' . Str::lower($request->correo) . '.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'correo' => 'Cuenta bloqueada temporalmente. Intente en ' . ceil($seconds / 60) . ' minutos o recupere su contraseña.',
            ])->withInput($request->only('correo'));
        }

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->correo)->first();

        // RF-02/RF-03: Validar credenciales
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            RateLimiter::hit($key, 900); // 15 minutos

            return back()->withErrors([
                'correo' => 'Correo o contraseña incorrectos. Verifique sus datos e intente nuevamente.',
            ])->withInput($request->only('correo'));
        }

        // Verificar que esté activo
        if (!$usuario->activo) {
            return back()->withErrors([
                'correo' => 'Esta cuenta está desactivada. Comuníquese con el administrador.',
            ])->withInput($request->only('correo'));
        }

        // Login exitoso
        RateLimiter::clear($key);
        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        // RF-05: Redirigir según rol
        $role = $usuario->rol?->nombre_rol ?? null;

        return match ($role) {
            'Administrador' => redirect()->intended(route('admin.dashboard')),
            'Técnico', 'Empleado' => redirect()->intended(route('tecnico.dashboard')),
            'Cliente'       => redirect()->intended(route('cliente.dashboard')),
            default         => redirect('/'),
        };
    }

    // RF-06: Cerrar sesión con confirmación (la confirmación es en la vista)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
