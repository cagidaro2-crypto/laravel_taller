<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoVehiculo;

class EstadoVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            [
                'nombre_estado' => 'Recibido',
                'descripcion' => 'Vehículo recibido en el taller.',
            ],
            [
                'nombre_estado' => 'En diagnóstico',
                'descripcion' => 'Vehículo en proceso de diagnóstico.',
            ],
            [
                'nombre_estado' => 'En reparación',
                'descripcion' => 'Vehículo actualmente en reparación.',
            ],
            [
                'nombre_estado' => 'Terminado',
                'descripcion' => 'Reparación terminada.',
            ],
            [
                'nombre_estado' => 'Entregado',
                'descripcion' => 'Vehículo entregado al cliente.',
            ],
        ];

        foreach ($estados as $estado) {
            EstadoVehiculo::updateOrCreate(
                ['nombre_estado' => $estado['nombre_estado']],
                $estado
            );
        }
    }
}