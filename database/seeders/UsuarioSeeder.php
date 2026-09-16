<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $idAdmin    = DB::table('roles')->where('nombre_rol', 'Administrador')->value('id_rol');
        $idTecnico  = DB::table('roles')->where('nombre_rol', 'Técnico')->value('id_rol');
        $idCliente  = DB::table('roles')->where('nombre_rol', 'Cliente')->value('id_rol');

        $usuarios = [
            [
                'id_rol'    => $idAdmin,
                'nombres'   => 'Admin',
                'apellidos' => 'Taller',
                'correo'    => 'admin@taller.com',
                'password'  => Hash::make('Admin123!'),
                'telefono'  => '3001234567',
                'activo'    => 1,
            ],
            [
                'id_rol'    => $idTecnico,
                'nombres'   => 'Carlos',
                'apellidos' => 'Técnico',
                'correo'    => 'tecnico@taller.com',
                'password'  => Hash::make('Tecnico123!'),
                'telefono'  => '3109876543',
                'activo'    => 1,
            ],
            [
                'id_rol'    => $idCliente,
                'nombres'   => 'Juan',
                'apellidos' => 'Cliente',
                'correo'    => 'cliente@taller.com',
                'password'  => Hash::make('Cliente123!'),
                'telefono'  => '3207654321',
                'activo'    => 1,
            ],
        ];

        foreach ($usuarios as $u) {
            // No duplicar si ya existe
            $existe = DB::table('usuarios')->where('correo', $u['correo'])->exists();
            if (!$existe) {
                $idUsuario = DB::table('usuarios')->insertGetId($u);

                // Si es cliente, crear registro en tabla clientes
                if ($u['id_rol'] === $idCliente) {
                    $yaCliente = DB::table('clientes')->where('id_usuario', $idUsuario)->exists();
                    if (!$yaCliente) {
                        DB::table('clientes')->insert([
                            'id_usuario'     => $idUsuario,
                            'tipo_documento' => 'CC',
                            'documento'      => '10' . rand(1000000, 9999999),
                            'nombres'        => $u['nombres'],
                            'apellidos'      => $u['apellidos'],
                            'telefono'       => $u['telefono'],
                            'correo'         => $u['correo'],
                            'direccion'      => 'Calle de prueba #1',
                        ]);
                    }
                }
            }
        }
    }
}
