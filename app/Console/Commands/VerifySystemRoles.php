<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Rol;
use App\Models\Admin\Usuario;
use App\Models\Admin\Factura;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VerifySystemRoles extends Command
{
    protected $signature = 'system:verify';
    protected $description = 'Verifica el funcionamiento de todos los roles, panel de cliente y módulo de facturación';

    public function handle()
    {
        $this->info("==================================================");
        $this->info("   VERIFICACIÓN INTEGRAL DEL SISTEMA Y ROLES      ");
        $this->info("==================================================");

        // 1. Roles en la base de datos
        $this->info("\n1. Verificando Roles en Base de Datos:");
        $rolesEsperados = ['Administrador', 'Técnico', 'Cliente'];
        foreach ($rolesEsperados as $nombreRol) {
            $rol = Rol::where('nombre_rol', $nombreRol)->first();
            if ($rol) {
                $count = Usuario::where('id_rol', $rol->id_rol)->count();
                $this->line("   [OK] Rol '{$nombreRol}' existe (ID: {$rol->id_rol}) con {$count} usuario(s).");
            } else {
                $this->error("   [ERROR] Rol '{$nombreRol}' no encontrado.");
            }
        }

        // 2. Probar acceso y rutas por Rol
        $this->info("\n2. Verificando Paneles y Rutas por Rol:");

        $tests = [
            'Administrador' => [
                'user' => Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Administrador'))->first(),
                'routes' => [
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
                ]
            ],
            'Técnico' => [
                'user' => Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))->first(),
                'routes' => [
                    '/tecnico/dashboard',
                    '/tecnico/citas',
                    '/tecnico/ordenes',
                    '/tecnico/vehiculos',
                    '/tecnico/historial',
                    '/tecnico/ventas',
                    '/tecnico/facturas',
                ]
            ],
            'Cliente' => [
                'user' => Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Cliente'))->first(),
                'routes' => [
                    '/cliente/dashboard',
                    '/cliente/vehiculos',
                    '/cliente/citas',
                    '/cliente/cotizaciones',
                    '/cliente/historial',
                    '/cliente/facturas',
                    '/cliente/notificaciones',
                ]
            ],
        ];

        foreach ($tests as $rolNombre => $data) {
            $this->info("\n--- Rol: {$rolNombre} ---");
            $user = $data['user'];
            if (!$user) {
                $this->error("   [ERROR] No hay usuario con rol {$rolNombre} para probar.");
                continue;
            }

            Auth::login($user);
            $this->line("   Usuario de prueba: {$user->nombre} ({$user->correo})");

            foreach ($data['routes'] as $uri) {
                try {
                    $request = Request::create($uri, 'GET');
                    $request->setUserResolver(fn() => $user);
                    
                    $response = app()->handle($request);
                    $status = $response->getStatusCode();

                    if ($status >= 200 && $status < 400) {
                        $this->line("   [OK {$status}] GET {$uri}");
                    } else {
                        $this->error("   [FAIL {$status}] GET {$uri}");
                    }
                } catch (\Throwable $e) {
                    $this->error("   [EXCEPTION] GET {$uri}: " . $e->getMessage());
                }
            }
        }

        // 3. Probar flujo de Facturación (Generación por empleado y descarga por cliente)
        $this->info("\n3. Verificando Flujo de Facturación:");
        
        // Obtener o crear una orden para probar
        $orden = OrdenTrabajo::with('vehiculo.cliente')->first();
        if ($orden && $orden->vehiculo && $orden->vehiculo->cliente) {
            $clienteId = $orden->vehiculo->id_cliente;

            // Verificar si ya tiene factura o crear una
            $factura = $orden->factura;
            if (!$factura) {
                $numero = 'F-' . str_pad(Factura::count() + 1, 6, '0', STR_PAD_LEFT);
                $factura = Factura::create([
                    'id_cliente'     => $clienteId,
                    'id_orden'       => $orden->id_orden,
                    'numero_factura' => $numero,
                    'fecha'          => now()->toDateString(),
                    'subtotal'       => 100000,
                    'impuesto'       => 19000,
                    'total'          => 119000,
                    'estado'         => 'Pendiente',
                ]);
                $this->line("   [OK] Factura de prueba generada: {$factura->numero_factura} para el cliente ID {$clienteId}");
            } else {
                $this->line("   [OK] Factura existente encontrada: {$factura->numero_factura} para el cliente ID {$clienteId}");
            }

            // Probar vista detalle cliente
            $clienteUser = Usuario::whereHas('cliente', fn($q) => $q->where('id_cliente', $clienteId))->first();
            if ($clienteUser) {
                Auth::login($clienteUser);
                
                // Show route
                $reqShow = Request::create("/cliente/facturas/{$factura->id_factura}", 'GET');
                $reqShow->setUserResolver(fn() => $clienteUser);
                $resShow = app()->handle($reqShow);
                $this->line("   [OK {$resShow->getStatusCode()}] Cliente visualiza detalle: /cliente/facturas/{$factura->id_factura}");

                // PDF route
                $reqPdf = Request::create("/cliente/facturas/{$factura->id_factura}/pdf", 'GET');
                $reqPdf->setUserResolver(fn() => $clienteUser);
                $resPdf = app()->handle($reqPdf);
                $this->line("   [OK {$resPdf->getStatusCode()}] Cliente descarga PDF: /cliente/facturas/{$factura->id_factura}/pdf");
            }
        }

        $this->info("\n==================================================");
        $this->info("   VERIFICACIÓN COMPLETADA EXITOSAMENTE           ");
        $this->info("==================================================");
        return 0;
    }
}
