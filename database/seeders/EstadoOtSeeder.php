<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoOt;

class EstadoOtSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            [
                'nombre' => 'Pendiente',
                'descripcion' => 'Orden creada y pendiente de atención.',
            ],
            [
                'nombre' => 'En proceso',
                'descripcion' => 'Trabajo actualmente en ejecución.',
            ],
            [
                'nombre' => 'En espera',
                'descripcion' => 'Trabajo detenido temporalmente.',
            ],
            [
                'nombre' => 'Terminado',
                'descripcion' => 'Trabajo finalizado.',
            ],
            [
                'nombre' => 'Entregado',
                'descripcion' => 'Vehículo entregado al cliente.',
            ],
            [
                'nombre' => 'Cancelado',
                'descripcion' => 'Orden cancelada.',
            ],
        ];

        foreach ($estados as $estado) {
            EstadoOt::updateOrCreate(
                ['nombre' => $estado['nombre']],
                $estado
            );
        }
    }
}