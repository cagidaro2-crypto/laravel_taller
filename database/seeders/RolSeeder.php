<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::updateOrCreate(
            ['nombre_rol' => 'Administrador'],
            ['nombre_rol' => 'Administrador']
        );

        Rol::updateOrCreate(
            ['nombre_rol' => 'Empleado'],
            ['nombre_rol' => 'Empleado']
        );

        Rol::updateOrCreate(
            ['nombre_rol' => 'Cliente'],
            ['nombre_rol' => 'Cliente']
        );
    }
}