<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SimulatLogin extends Command
{
    protected $signature = 'simulat:login {correo=admin@test.com} {password=password123}';
    protected $description = 'Simula un login POST idéntico a lo que hace el navegador';

    public function handle()
    {
        $correo = $this->argument('correo');
        $password = $this->argument('password');

        $this->info('=== SIMULACIÓN DE LOGIN ===');
        $this->newLine();
        $this->line("Correo: $correo");
        $this->line("Contraseña: " . str_repeat('*', strlen($password)));
        $this->newLine();

        // Simular lo que hace LoginController->login()
        $this->info('1. Buscando usuario...');
        $usuario = Usuario::where('correo', $correo)->first();

        if (!$usuario) {
            $this->error("✗ Usuario no encontrado");
            return;
        }

        $this->line("✓ Usuario encontrado: {$usuario->nombre}");

        // Verificar contraseña
        $this->info('2. Verificando contraseña...');
        if (!Hash::check($password, $usuario->password)) {
            $this->error("✗ Contraseña incorrecta");
            return;
        }

        $this->line("✓ Contraseña correcta");

        // Verificar activo
        $this->info('3. Verificando estado...');
        if (!$usuario->activo) {
            $this->error("✗ Usuario inactivo");
            return;
        }

        $this->line("✓ Usuario activo");

        // Cargar rol
        $this->info('4. Cargando rol...');
        $usuario->load('rol');

        if (!$usuario->rol) {
            $this->error("✗ Usuario sin rol");
            return;
        }

        $this->line("✓ Rol: {$usuario->rol->nombre_rol}");

        // Login
        $this->info('5. Haciendo login...');
        Auth::login($usuario, false);

        if (!Auth::check()) {
            $this->error("✗ Login falló");
            return;
        }

        $this->line("✓ Login exitoso");

        // Ver dónde redirige
        $this->info('6. Determinando redirección...');
        $role = $usuario->rol->nombre_rol;
        $redirect = match ($role) {
            'Administrador' => route('admin.dashboard'),
            'Técnico', 'Empleado' => route('tecnico.dashboard'),
            'Cliente' => route('cliente.dashboard'),
            default => '/',
        };

        $this->line("✓ Redirigirá a: $redirect");

        $this->newLine();
        $this->info('=== SIMULACIÓN EXITOSA ===');
        $this->line("Usuario autenticado como: " . Auth::user()->correo);
    }
}
