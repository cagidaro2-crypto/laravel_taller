<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Usuario;
use App\Models\Admin\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DiagnosticoSesion extends Command
{
    protected $signature = 'diagnostico:sesion';
    protected $description = 'Diagnostica problemas de inicio de sesión';

    public function handle()
    {
        $this->info('=== DIAGNÓSTICO DE SESIÓN ===');
        $this->newLine();

        // 1. Verificar conexión a BD
        $this->info('1. Verificando conexión a base de datos...');
        try {
            DB::connection()->getPdo();
            $this->line('✓ Conexión OK');
        } catch (\Exception $e) {
            $this->error('✗ Error de conexión: ' . $e->getMessage());
            return;
        }

        // 2. Verificar tabla usuarios
        $this->info('2. Verificando tabla usuarios...');
        if (Schema::hasTable('usuarios')) {
            $this->line('✓ Tabla existe');
            $count = Usuario::count();
            $this->line("   Total de usuarios: $count");
            
            if ($count > 0) {
                $this->line('   Usuarios registrados:');
                Usuario::with('rol')->get()->each(function($user) {
                    $rol = $user->rol?->nombre_rol ?? 'Sin rol';
                    $this->line("   - {$user->nombre} ({$user->correo}) - Rol: {$rol} - Activo: " . ($user->activo ? 'Sí' : 'No'));
                });
            } else {
                $this->warn('   ⚠ No hay usuarios registrados');
            }
        } else {
            $this->error('✗ Tabla usuarios no existe');
            return;
        }

        // 3. Verificar tabla roles
        $this->info('3. Verificando tabla roles...');
        if (Schema::hasTable('roles')) {
            $this->line('✓ Tabla existe');
            $roles = Rol::count();
            $this->line("   Total de roles: $roles");
            
            if ($roles > 0) {
                $this->line('   Roles registrados:');
                Rol::all()->each(function($rol) {
                    $this->line("   - {$rol->nombre_rol}");
                });
            } else {
                $this->warn('   ⚠ No hay roles registrados');
            }
        } else {
            $this->error('✗ Tabla roles no existe');
            return;
        }

        // 4. Verificar configuración de autenticación
        $this->info('4. Verificando configuración de autenticación...');
        $this->line('   Guard: ' . config('auth.defaults.guard'));
        $this->line('   Provider: ' . config('auth.guards.web.provider'));
        $this->line('   Model: ' . config('auth.providers.users.model'));

        // 5. Crear usuario de prueba si no existe
        $this->info('5. Verificando usuario de prueba...');
        $testUser = Usuario::where('correo', 'admin@test.com')->first();
        
        if (!$testUser) {
            $this->warn('   ⚠ Usuario de prueba no existe, creando...');
            
            $adminRole = Rol::where('nombre_rol', 'Administrador')->first();
            if (!$adminRole) {
                $this->error('   ✗ No existe rol Administrador');
                return;
            }
            
            $testUser = Usuario::create([
                'id_rol' => $adminRole->id_rol,
                'nombre' => 'Admin Prueba',
                'correo' => 'admin@test.com',
                'password' => Hash::make('password123'),
                'telefono' => '1234567890',
                'activo' => true,
            ]);
            
            $this->line('✓ Usuario de prueba creado:');
            $this->line("   Correo: admin@test.com");
            $this->line("   Contraseña: password123");
        } else {
            $this->line('✓ Usuario de prueba existe:');
            $this->line("   Correo: {$testUser->correo}");
            $this->line("   Nombre: {$testUser->nombre}");
            $this->line("   Activo: " . ($testUser->activo ? 'Sí' : 'No'));
        }

        $this->newLine();
        $this->info('=== DIAGNÓSTICO COMPLETADO ===');
        $this->info('Intenta iniciar sesión con: admin@test.com / password123');
    }
}
