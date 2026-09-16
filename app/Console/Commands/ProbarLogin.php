<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Usuario;
use Illuminate\Support\Facades\Hash;

class ProbarLogin extends Command
{
    protected $signature = 'probar:login {correo} {password}';
    protected $description = 'Probar las credenciales de login';

    public function handle()
    {
        $correo = $this->argument('correo');
        $password = $this->argument('password');

        $this->info("Probando login con: {$correo}");
        $this->newLine();

        // Buscar usuario
        $usuario = Usuario::where('correo', $correo)->first();

        if (!$usuario) {
            $this->error("✗ Usuario no encontrado");
            return 1;
        }

        $this->info("✓ Usuario encontrado");
        $this->line("  - ID: {$usuario->id_usuario}");
        $this->line("  - Correo: {$usuario->correo}");
        $this->line("  - Activo: " . ($usuario->activo ? "Sí" : "No"));
        $this->line("  - ID Rol: {$usuario->id_rol}");

        // Verificar contraseña
        if (Hash::check($password, $usuario->password)) {
            $this->info("✓ Contraseña correcta");
        } else {
            $this->error("✗ Contraseña incorrecta");
            return 1;
        }

        // Verificar que esté activo
        if (!$usuario->activo) {
            $this->error("✗ Usuario desactivado");
            return 1;
        }

        $this->info("✓ Usuario activo");

        // Verificar rol
        if ($usuario->rol) {
            $this->info("✓ Rol asignado: " . $usuario->rol->nombre_rol);
        } else {
            $this->error("✗ Usuario sin rol asignado");
            return 1;
        }

        $this->newLine();
        $this->info("✓ Login debería funcionar correctamente");
        return 0;
    }
}
