<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaProducto;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Pinturas',
                'descripcion' => 'Pinturas utilizadas para vehículos.',
            ],
            [
                'nombre' => 'Repuestos',
                'descripcion' => 'Repuestos y piezas para vehículos.',
            ],
            [
                'nombre' => 'Herramientas',
                'descripcion' => 'Herramientas utilizadas en el taller.',
            ],
            [
                'nombre' => 'Consumibles',
                'descripcion' => 'Materiales de consumo del taller.',
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Accesorios para vehículos.',
            ],
        ];

        foreach ($categorias as $categoria) {
            CategoriaProducto::updateOrCreate(
                ['nombre' => $categoria['nombre']],
                $categoria
            );
        }
    }
}