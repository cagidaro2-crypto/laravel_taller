<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsertRolesCommand
{
    public static function insert()
    {
        // Deshabilitar restricciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpiar datos existentes
        DB::table('usuarios')->truncate();
        DB::table('roles')->truncate();
        
        // Rehabilitar restricciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Crear roles
        $adminRole = DB::table('roles')->insertGetId([
            'nombre_rol' => 'Administrador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $empleadoRole = DB::table('roles')->insertGetId([
            'nombre_rol' => 'Empleado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $clienteRole = DB::table('roles')->insertGetId([
            'nombre_rol' => 'Cliente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuarios de prueba
        DB::table('usuarios')->insert([
            [
                'id_rol' => $adminRole,
                'nombre' => 'Administrador Principal',
                'correo' => 'admin@taller.com',
                'password' => Hash::make('admin123'),
                'telefono' => '123456789',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rol' => $empleadoRole,
                'nombre' => 'Técnico Principal',
                'correo' => 'tecnico@taller.com',
                'password' => Hash::make('tecnico123'),
                'telefono' => '987654321',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rol' => $clienteRole,
                'nombre' => 'Juan Pérez',
                'correo' => 'cliente@example.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '555666777',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return 'Roles y usuarios creados correctamente';
    }
}
