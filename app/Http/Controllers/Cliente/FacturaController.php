<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Admin\Factura;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    use HasClienteProfile;

    /**
     * Listar facturas del cliente autenticado
     */
    public function index(Request $request)
    {
        $clienteId = $this->clienteId();

        $query = Factura::where('id_cliente', $clienteId)
            ->with(['orden.vehiculo', 'cotizacion.vehiculo', 'pagos'])
            ->orderByDesc('fecha');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $facturas = $query->paginate(12);

        $totales = [
            'total'     => Factura::where('id_cliente', $clienteId)->count(),
            'pendientes'=> Factura::where('id_cliente', $clienteId)->where('estado', 'Pendiente')->count(),
            'pagadas'   => Factura::where('id_cliente', $clienteId)->where('estado', 'Pagada')->count(),
        ];

        return view('cliente.facturas.index', compact('facturas', 'totales'));
    }

    /**
     * Ver detalle completo de una factura
     */
    public function show(Factura $factura)
    {
        abort_if($factura->id_cliente !== $this->clienteId(), 403, 'No tienes permiso para ver esta factura.');

        $factura->load([
            'cliente.usuario',
            'orden.vehiculo',
            'orden.servicios.servicio',
            'orden.productos.producto',
            'orden.consumoMateriales.producto',
            'cotizacion.vehiculo',
            'cotizacion.servicios.servicio',
            'cotizacion.productos.producto',
            'pagos'
        ]);

        return view('cliente.facturas.show', compact('factura'));
    }

    /**
     * Descargar factura en PDF
     */
    public function pdf(Factura $factura)
    {
        abort_if($factura->id_cliente !== $this->clienteId(), 403, 'No tienes permiso para descargar esta factura.');

        $factura->load([
            'cliente.usuario',
            'orden.vehiculo',
            'orden.servicios.servicio',
            'orden.productos.producto',
            'orden.consumoMateriales.producto',
            'cotizacion.vehiculo',
            'cotizacion.servicios.servicio',
            'cotizacion.productos.producto',
            'pagos'
        ]);

        $pdf = Pdf::loadView('facturas.pdf_template', compact('factura'));

        return $pdf->download("Factura_{$factura->numero_factura}.pdf");
    }

    /**
     * Marcar factura como pagada
     */
    public function marcarPagada(Factura $factura)
    {
        abort_if($factura->id_cliente !== $this->clienteId(), 403, 'No tienes permiso para actualizar esta factura.');

        // Verificar que no esté ya pagada
        if ($factura->estado === 'Pagada') {
            return response()->json(['message' => 'Esta factura ya está pagada'], 422);
        }

        try {
            // Actualizar estado a pagado
            $factura->update([
                'estado' => 'Pagada',
                'fecha_pago' => now(),
            ]);

            // Si no existe pago, crear uno
            if ($factura->pagos()->whereNull('fecha_pago')->doesntExist()) {
                $factura->pagos()->create([
                    'monto' => $factura->total,
                    'metodo_pago' => 'En línea',
                    'referencia' => 'Pago realizado por cliente',
                    'fecha_pago' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Factura marcada como pagada',
                'estado' => 'Pagada',
                'total_pagado' => $factura->total,
                'saldo_pendiente' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar el pago: ' . $e->getMessage()], 500);
        }
    }
