<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Mail\NotificacionEstadoVehiculoMail;
use App\Models\Admin\EstadoOt;
use App\Models\Admin\Notificacion;
use App\Models\Tecnico\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrdenTrabajoController extends Controller
{
    public function index(Request $request)
    {
        $ordenes = OrdenTrabajo::with(['vehiculo.cliente.usuario', 'estado'])
            ->where('id_usuario', Auth::id())
            ->orderByDesc('fecha_ingreso')
            ->paginate(15);

        return view('tecnico.ordenes.index', compact('ordenes'));
    }

    public function show(OrdenTrabajo $ordene)
    {
        $ordene->load(['vehiculo.cliente.usuario', 'estado', 'servicios.servicio', 'productos.producto']);
        return view('tecnico.ordenes.show', compact('ordene'));
    }

    public function actualizarEstado(Request $request, OrdenTrabajo $ordene)
    {
        // Verificar que la orden pertenezca al técnico
        abort_if($ordene->id_usuario !== Auth::id(), 403, 'No autorizado para actualizar esta orden');

        $request->validate([
            'id_estado'    => 'required|exists:estados_ot,id_estado',
            'observaciones'=> 'nullable|string|max:500',
        ]);

        // BUG #11: Agregar transacción
        DB::transaction(function () use ($request, $ordene) {
            $estadoAnterior = $ordene->estado->nombre;
            $ordene->update(['id_estado' => $request->id_estado]);

            if ($request->filled('observaciones')) {
                $ordene->update(['observaciones' => $request->observaciones]);
            }

            $estadoNuevo = $ordene->refresh()->estado->nombre;

            // Crear notificación al cliente
            $this->notificarCambioEstado($ordene, $estadoAnterior, $estadoNuevo);
        });

        return back()->with('success', "Estado de la orden actualizado correctamente.");
    }

    /**
     * Actualizar estado del vehículo dentro de una orden
     */
    public function actualizarEstadoVehiculo(Request $request, OrdenTrabajo $ordene)
    {
        // Verificar autorización
        abort_if($ordene->id_usuario !== Auth::id(), 403, 'No autorizado');

        $request->validate([
            'id_estado_vehiculo' => 'required|exists:estado_vehiculo,id_estado',
            'descripcion'        => 'nullable|string|max:500',
        ]);

        $vehiculo = $ordene->vehiculo;
        $estadoAnterior = $vehiculo->estado->nombre ?? 'Desconocido';

        // Actualizar estado del vehículo
        $vehiculo->update(['id_estado' => $request->id_estado_vehiculo]);

        $estadoNuevo = $vehiculo->refresh()->estado->nombre;

        // Registrar en historial (si existe HistorialVehiculo)
        if (method_exists($vehiculo, 'historial')) {
            $vehiculo->historial()->create([
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $estadoNuevo,
                'descripcion'     => $request->descripcion ?? 'Cambio de estado de vehículo',
                'id_usuario'      => Auth::id(),
                'fecha'           => now()->toDateString(),
                'valor'           => 0,
            ]);
        }

        // Notificar al cliente sobre cambio de estado del vehículo
        $this->notificarCambioEstadoVehiculo($ordene, $vehiculo, $estadoAnterior, $estadoNuevo);

        return back()->with('success', "Estado del vehículo actualizado a: {$estadoNuevo}");
    }

    /**
     * Crear notificación cuando cambia el estado de la orden
     */
    private function notificarCambioEstado(OrdenTrabajo $ordene, $estadoAnterior, $estadoNuevo)
    {
        try {
            $cliente = $ordene->vehiculo->cliente;
            
            $notificacion = Notificacion::create([
                'id_usuario_destinatario' => $cliente->id_usuario,
                'id_orden'                => $ordene->id_orden,
                'id_vehiculo'             => $ordene->id_vehiculo,
                'tipo'                    => 'estado_orden_cambio',
                'titulo'                  => "Orden #{$ordene->id_orden} - Cambio de Estado",
                'descripcion'             => "El estado de su orden ha cambiado de '{$estadoAnterior}' a '{$estadoNuevo}'",
                'estado_anterior'         => $estadoAnterior,
                'estado_nuevo'            => $estadoNuevo,
                'enviada_por_email'       => false,
            ]);

            // Enviar email (implementar después)
            // Mail::to($cliente->usuario->correo)->send(...);

        } catch (\Exception $e) {
            Log::warning("Error al crear notificación de orden: {$e->getMessage()}");
        }
    }

    /**
     * Crear notificación cuando cambia el estado del vehículo
     */
    private function notificarCambioEstadoVehiculo(OrdenTrabajo $ordene, $vehiculo, $estadoAnterior, $estadoNuevo)
    {
        try {
            $cliente = $vehiculo->cliente;

            $mensajeEstado = $this->getMensajeEstadoVehiculo($estadoNuevo);

            $notificacion = Notificacion::create([
                'id_usuario_destinatario' => $cliente->id_usuario,
                'id_orden'                => $ordene->id_orden,
                'id_vehiculo'             => $vehiculo->id_vehiculo,
                'tipo'                    => 'estado_vehiculo_cambio',
                'titulo'                  => "{$vehiculo->placa} - {$mensajeEstado}",
                'descripcion'             => "Tu vehículo {$vehiculo->placa} ({$vehiculo->marca} {$vehiculo->modelo}) ha cambiado a estado: {$estadoNuevo}",
                'estado_anterior'         => $estadoAnterior,
                'estado_nuevo'            => $estadoNuevo,
                'enviada_por_email'       => false,
            ]);

            // Enviar email al cliente
            try {
                Mail::to($cliente->usuario->correo)->send(
                    new NotificacionEstadoVehiculoMail($notificacion)
                );
                
                $notificacion->update(['enviada_por_email' => true, 'fecha_envio' => now()]);
                
            } catch (\Exception $mailError) {
                Log::warning("Error al enviar email de notificación: {$mailError->getMessage()}");
                // No bloquear la operación si falla el email
            }

        } catch (\Exception $e) {
            Log::warning("Error al crear notificación de vehículo: {$e->getMessage()}");
        }
    }

    /**
     * Obtener mensaje amigable según el estado del vehículo
     */
    private function getMensajeEstadoVehiculo($estado): string
    {
        $mensajes = [
            'En Diagnóstico'      => '🔧 Tu vehículo está siendo diagnosticado',
            'En Reparación'       => '🛠️ Tu vehículo está siendo reparado',
            'En Limpieza'         => '🧹 Tu vehículo está siendo limpiado',
            'Listo para Entregar' => '✅ Tu vehículo está listo para recoger',
            'Entregado'           => '🚗 Tu vehículo ha sido entregado',
        ];

        return $mensajes[$estado] ?? "Tu vehículo está: {$estado}";
    }

    /**
     * Generar factura para el cliente a partir de la orden de trabajo
     */
    public function generarFactura(OrdenTrabajo $ordene)
    {
        if ($ordene->factura) {
            return back()->with('info', "Esta orden ya tiene una factura generada (#{$ordene->factura->numero_factura}).");
        }

        $ordene->load(['vehiculo.cliente.usuario', 'servicios', 'productos', 'consumoMateriales']);

        $cliente = $ordene->vehiculo?->cliente;
        if (!$cliente) {
            return back()->with('error', 'El vehículo de esta orden no tiene un cliente asignado.');
        }

        $totalServicios = (float) $ordene->servicios->sum('subtotal');
        $totalProductos = (float) $ordene->productos->sum('subtotal');
        $totalMateriales = (float) $ordene->consumoMateriales->sum('subtotal');
        $subtotal = $totalServicios + $totalProductos + $totalMateriales;

        if ($subtotal <= 0 && $ordene->subtotal > 0) {
            $subtotal = (float) $ordene->subtotal;
        } elseif ($subtotal <= 0 && $ordene->total > 0) {
            $subtotal = round((float) $ordene->total / 1.19, 2);
        }

        $impuesto = round($subtotal * 0.19, 2);
        $total = $subtotal + $impuesto;

        $factura = DB::transaction(function () use ($ordene, $cliente, $subtotal, $impuesto, $total) {
            $numero = 'F-' . str_pad(\App\Models\Admin\Factura::count() + 1, 6, '0', STR_PAD_LEFT);

            $factura = \App\Models\Admin\Factura::create([
                'id_cliente'     => $cliente->id_cliente,
                'id_orden'       => $ordene->id_orden,
                'numero_factura' => $numero,
                'fecha'          => now()->toDateString(),
                'subtotal'       => $subtotal,
                'impuesto'       => $impuesto,
                'total'          => $total,
                'estado'         => 'Pendiente',
            ]);

            if ($ordene->total <= 0) {
                $ordene->update([
                    'subtotal' => $subtotal,
                    'impuesto' => $impuesto,
                    'total'    => $total,
                ]);
            }

            if ($cliente->id_usuario) {
                Notificacion::create([
                    'id_usuario_destinatario' => $cliente->id_usuario,
                    'id_orden'               => $ordene->id_orden,
                    'id_vehiculo'            => $ordene->id_vehiculo,
                    'tipo'                   => 'factura_generada',
                    'titulo'                 => "Factura Generada #{$numero}",
                    'descripcion'            => "El empleado ha generado la factura {$numero} por un valor de $" . number_format($total, 2) . " para tu vehículo {$ordene->vehiculo->placa}. Ya puedes consultarla y descargarla en PDF desde tu portal.",
                    'leida'                  => false,
                ]);
            }

            return $factura;
        });

        return back()->with('success', "Factura {$factura->numero_factura} generada exitosamente para el cliente.");
    }
}

