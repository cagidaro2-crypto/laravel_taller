<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Usuario;
use App\Models\Admin\Rol;

class DiagnosticoLogin extends Command
{
    protected $signature = 'diagnostico:login';
    protected $description = 'Diagnóstico del sistema de login';

    public function handle()
    {
        $this->info('=== DIAGNÓSTICO DE LOGIN ===');
        $this->newLine();

        // 1. Verificar usuarios
        $this->info('1. VERIFICANDO TABLA "usuarios":');
        $usuarios = Usuario::all();
        $this->line("   Total de usuarios: " . count($usuarios));

        if (count($usuarios) > 0) {
            $this->line("   Usuarios encontrados:");
            foreach ($usuarios as $usuario) {
                $password_status = !empty($usuario->password) ? "✓ PRESENTE" : "✗ VACIO";
                $this->line("   - ID: {$usuario->id_usuario}, Correo: {$usuario->correo}, Activo: {$usuario->activo}, ID_Rol: {$usuario->id_rol}, Password: {$password_status}");
            }
        } else {
            $this->warn("   ⚠️  No hay usuarios en la base de datos");
        }

        $this->newLine();

        // 2. Verificar roles
        $this->info('2. VERIFICANDO TABLA "roles":');
        $roles = Rol::all();
        $this->line("   Total de roles: " . count($roles));

        if (count($roles) > 0) {
            $this->line("   Roles encontrados:");
            foreach ($roles as $rol) {
                $this->line("   - ID: {$rol->id_rol}, Nombre: {$rol->nombre_rol}");
            }
        } else {
            $this->warn("   ⚠️  No hay roles en la base de datos");
        }

        $this->newLine();

        // 3. Verificar relaciones
        if (count($usuarios) > 0) {
            $this->info('3. VERIFICANDO RELACIONES USUARIO-ROL:');
            $usuariosConRol = Usuario::with('rol')->get();
            foreach ($usuariosConRol as $usuario) {
                $rolNombre = $usuario->rol ? $usuario->rol->nombre_rol : "SIN ROL";
                $this->line("   - Usuario: {$usuario->correo} => Rol: {$rolNombre}");
            }
            $this->newLine();
        }

        // 4. Resumen
        $this->info('4. RESUMEN DEL DIAGNÓSTICO:');
        if (count($usuarios) === 0) {
            $this->error("   ✗ No hay usuarios. Se necesita ejecutar el seeder.");
            return 1;
        } elseif (count($roles) === 0) {
            $this->error("   ✗ No hay roles definidos. Se necesita ejecutar el seeder.");
            return 1;
        } else {
            $usuariosSinPassword = Usuario::whereNull('password')->orWhere('password', '')->count();
            if ($usuariosSinPassword > 0) {
                $this->warn("   ⚠️  Hay {$usuariosSinPassword} usuario(s) sin contraseña");
            } else {
                $this->info("   ✓ Sistema de login está correctamente configurado");
                return 0;
            }
        }

        $this->newLine();
        $this->info('=== FIN DEL DIAGNÓSTICO ===');
    }
}
