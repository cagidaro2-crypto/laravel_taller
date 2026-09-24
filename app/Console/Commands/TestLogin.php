<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestLogin extends Command
{
    protected $signature = 'test:login';
    protected $description = 'Prueba el sistema de login manualmente';

    public function handle()
    {
        $this->info('=== TEST DE LOGIN ===');
        $this->newLine();

        // 1. Buscar usuario
        $this->info('1. Buscando usuario admin@test.com...');
        $usuario = Usuario::where('correo', 'admin@test.com')->first();

        if (!$usuario) {
            $this->error('✗ Usuario no encontrado');
            return;
        }

        $this->line('✓ Usuario encontrado');
        $this->line("  Nombre: {$usuario->nombre}");
        $this->line("  ID: {$usuario->id_usuario}");
        $this->line("  Activo: " . ($usuario->activo ? 'Sí' : 'No'));

        // 2. Verificar contraseña
        $this->info('2. Verificando contraseña...');
        $passwordValido = Hash::check('password123', $usuario->password);

        if (!$passwordValido) {
            $this->error('✗ Contraseña incorrecta');
            return;
        }

        $this->line('✓ Contraseña correcta');

        // 3. Cargar rol
        $this->info('3. Cargando rol...');
        $usuario->load('rol');

        if (!$usuario->rol) {
            $this->error('✗ Usuario sin rol');
            return;
        }

        $this->line('✓ Rol cargado: ' . $usuario->rol->nombre_rol);

        // 4. Intentar login
        $this->info('4. Intentando login...');
        Auth::login($usuario);

        if (Auth::check()) {
            $this->line('✓ Login exitoso');
            $this->line("  Usuario autenticado: {$usuario->correo}");
            $this->line("  Rol: {$usuario->rol->nombre_rol}");
        } else {
            $this->error('✗ Login falló');
        }

        $this->newLine();
        $this->info('=== TEST COMPLETADO ===');
    }
}
