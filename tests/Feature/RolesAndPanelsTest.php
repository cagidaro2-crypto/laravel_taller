<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin\Usuario;

class RolesAndPanelsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'mysql']);
    }
    public function test_admin_routes()
    {
        $admin = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Administrador'))->first();
        $this->assertNotNull($admin, 'Admin user must exist');

        $routes = [
            '/admin/dashboard',
            '/admin/usuarios',
            '/admin/servicios',
            '/admin/productos',
            '/admin/proveedores',
            '/admin/inventario',
            '/admin/ordenes',
            '/admin/cotizaciones',
            '/admin/facturas',
            '/admin/ventas',
            '/admin/reportes',
            '/admin/vehiculos',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get($route);
            echo "Admin GET {$route}: Status " . $response->status() . "\n";
            $this->assertLessThan(400, $response->status(), "Admin route {$route} returned error {$response->status()}");
        }
    }

    public function test_tecnico_routes()
    {
        $tecnico = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))->first();
        $this->assertNotNull($tecnico, 'Tecnico user must exist');

        $routes = [
            '/tecnico/dashboard',
            '/tecnico/citas',
            '/tecnico/ordenes',
            '/tecnico/vehiculos',
            '/tecnico/historial',
            '/tecnico/ventas',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($tecnico)->get($route);
            echo "Tecnico GET {$route}: Status " . $response->status() . "\n";
            $this->assertLessThan(400, $response->status(), "Tecnico route {$route} returned error {$response->status()}");
        }
    }

    public function test_cliente_routes()
    {
        $cliente = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Cliente'))->first();
        $this->assertNotNull($cliente, 'Cliente user must exist');

        $routes = [
            '/cliente/dashboard',
            '/cliente/vehiculos',
            '/cliente/vehiculos/1',
            '/cliente/citas',
            '/cliente/cotizaciones',
            '/cliente/historial',
            '/cliente/historial/1',
            '/cliente/notificaciones',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($cliente)->get($route);
            echo "Cliente GET {$route}: Status " . $response->status() . "\n";
            $this->assertLessThan(400, $response->status(), "Cliente route {$route} returned error {$response->status()}");
        }
    }
}
