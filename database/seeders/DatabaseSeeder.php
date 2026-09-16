<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Roles ──────────────────────────────────────────────
        $idAdmin   = DB::table('roles')->insertGetId(['nombre_rol' => 'Administrador', 'created_at' => now(), 'updated_at' => now()]);
        $idTecnico = DB::table('roles')->insertGetId(['nombre_rol' => 'Técnico',       'created_at' => now(), 'updated_at' => now()]);
        $idCliente = DB::table('roles')->insertGetId(['nombre_rol' => 'Cliente',       'created_at' => now(), 'updated_at' => now()]);

        // ── 2. Estados de Órdenes de Trabajo ─────────────────────
        DB::table('estados_ot')->insert([
            ['nombre' => 'Pendiente',    'descripcion' => 'Orden recién creada',        'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'En proceso',   'descripcion' => 'Trabajo en curso',           'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'En espera',    'descripcion' => 'Trabajo detenido temporalmente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Terminado',    'descripcion' => 'Trabajo finalizado',         'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Entregado',    'descripcion' => 'Vehículo entregado al cliente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cancelado',    'descripcion' => 'Orden cancelada',            'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── 3. Usuarios ───────────────────────────────────────────
        $idUsuarioAdmin = DB::table('usuarios')->insertGetId([
            'id_rol'     => $idAdmin,
            'nombre'     => 'Admin Taller',
            'correo'     => 'admin@taller.com',
            'password'   => Hash::make('Admin123!'),
            'telefono'   => '3001234567',
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idUsuarioTecnico = DB::table('usuarios')->insertGetId([
            'id_rol'     => $idTecnico,
            'nombre'     => 'Carlos Técnico',
            'correo'     => 'tecnico@taller.com',
            'password'   => Hash::make('Tecnico123!'),
            'telefono'   => '3109876543',
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idUsuarioCliente = DB::table('usuarios')->insertGetId([
            'id_rol'     => $idCliente,
            'nombre'     => 'Juan Cliente',
            'correo'     => 'cliente@taller.com',
            'password'   => Hash::make('Cliente123!'),
            'telefono'   => '3207654321',
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── 4. Registro en tabla clientes (solo el usuario cliente) ─
        DB::table('clientes')->insert([
            'id_usuario' => $idUsuarioCliente,
            'documento'  => '1012345678',
            'direccion'  => 'Calle 1 # 2-3, Bogotá',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
