<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class PasswordController extends Controller
{
    // Cambio de contraseña (usuario autenticado)
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password_actual'  => 'required',
            'password_nueva'   => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ], [
            'password_actual.required'       => 'Ingrese su contraseña actual.',
            'password_nueva.required'        => 'La nueva contraseña es obligatoria.',
            'password_nueva.min'             => 'La contraseña debe tener al menos 8 caracteres.',
            'password_nueva.regex'           => 'La contraseña debe contener mayúscula, número y carácter especial.',
            'password_nueva.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Auth::user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        // No permitir usar la misma contraseña
        if (Hash::check($request->password_nueva, $usuario->password)) {
            return back()->with('error', 'La nueva contraseña no puede ser igual a la actual.');
        }

        // Actualizar contraseña
        $usuario->update([
            'password' => Hash::make($request->password_nueva),
        ]);

        return back()->with('success', 'Contraseña cambió correctamente.');
    }

    // Recuperación de contraseña (sin autenticación)
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|exists:usuarios,correo',
        ], [
            'correo.required' => 'Ingrese su correo electrónico.',
            'correo.email'    => 'Ingrese un correo válido.',
            'correo.exists'   => 'Este correo no está registrado en el sistema.',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        // Generar token de recuperación
        $token = Hash::make($usuario->id_usuario . '-' . now()->timestamp);

        // Guardar en cache por 30 minutos
        cache()->put(
            'password_reset_' . $usuario->id_usuario,
            [
                'token'      => $token,
                'expires_at' => now()->addMinutes(30),
            ],
            now()->addMinutes(30)
        );

        // En producción: enviar email
        // Mail::send('emails.reset-password', [...], function ($mail) {...});

        // Para desarrollo: mostrar el link
        $resetLink = route('password.reset.form', ['id' => $usuario->id_usuario, 'token' => $token]);

        return back()->with('success', "Se ha enviado un enlace de recuperación a {$request->correo}. (Desarrollo: <a href='{$resetLink}' class='underline'>Link aquí</a>)");
    }

    public function showResetForm(Request $request)
    {
        $usuario = Usuario::findOrFail($request->id);
        $cached  = cache()->get('password_reset_' . $usuario->id_usuario);

        // Verificar token y que no haya expirado
        if (!$cached || $cached['token'] !== $request->token || $cached['expires_at']->isPast()) {
            return redirect()->route('login')->with('error', 'El enlace de recuperación ha expirado o es inválido.');
        }

        return view('auth.reset-password', compact('usuario', 'request'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id'                  => 'required|exists:usuarios,id_usuario',
            'token'               => 'required',
            'password'            => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ], [
            'password.required' => 'Ingrese una nueva contraseña.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex'    => 'La contraseña debe contener mayúscula, número y carácter especial.',
        ]);

        $usuario = Usuario::findOrFail($request->id);
        $cached  = cache()->get('password_reset_' . $usuario->id_usuario);

        // Verificar token nuevamente
        if (!$cached || $cached['token'] !== $request->token || $cached['expires_at']->isPast()) {
            return redirect()->route('login')->with('error', 'El enlace de recuperación ha expirado.');
        }

        // Actualizar contraseña
        $usuario->update(['password' => Hash::make($request->password)]);

        // Limpiar token
        cache()->forget('password_reset_' . $usuario->id_usuario);

        return redirect()->route('login')->with('success', 'Contraseña recuperada correctamente. Puedes iniciar sesión con tu nueva contraseña.');
    }
}
