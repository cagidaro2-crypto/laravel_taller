<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;

// Auth
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->middleware('guest');

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

// RUTA DE PRUEBA - ELIMINAR DESPUÉS DE DIAGNOSTICAR
Route::get('/test-login', fn() => view('auth.login'))->name('test.login');

require __DIR__.'/admin.php';
require __DIR__.'/tecnico.php';
require __DIR__.'/cliente.php';
