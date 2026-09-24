<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;

// Auth
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Login simple para testing
Route::get('/login-simple', fn() => view('auth.login-simple'))->name('login.simple');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Cambio de contraseña (solo autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/cambiar-contrasena', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/cambiar-contrasena', [PasswordController::class, 'changePassword'])->name('password.update');
});

// Recuperación de contraseña (sin autenticación)
Route::middleware('guest')->group(function () {
    Route::get('/recuperar-contrasena', [PasswordController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/recuperar-contrasena', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-contrasena/{id}/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-contrasena', [PasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::get('/', fn() => view('index'))->name('inicio');

// RUTA DE DIAGNÓSTICO - Para probar login
Route::get('/debug/login-test', function() {
    $usuarios = \App\Models\Admin\Usuario::with('rol')->get();
    return [
        'usuarios' => $usuarios,
        'config_auth' => [
            'guard' => config('auth.defaults.guard'),
            'provider' => config('auth.guards.web.provider'),
            'model' => config('auth.providers.users.model'),
        ]
    ];
});

Route::post('/debug/login-test', function(\Illuminate\Http\Request $request) {
    \Log::info('LOGIN TEST POST', [
        'correo' => $request->correo,
        'has_password' => $request->has('password'),
        'csrf_ok' => $request->has('_token'),
    ]);

    $usuario = \App\Models\Admin\Usuario::where('correo', $request->correo)->first();
    
    if (!$usuario) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    $passwordOk = \Illuminate\Support\Facades\Hash::check($request->password, $usuario->password);
    
    return response()->json([
        'usuario_existe' => true,
        'nombre' => $usuario->nombre,
        'activo' => $usuario->activo,
        'password_correcto' => $passwordOk,
        'rol' => $usuario->rol?->nombre_rol,
    ]);
});

require __DIR__.'/admin.php';
require __DIR__.'/tecnico.php';
require __DIR__.'/cliente.php';
