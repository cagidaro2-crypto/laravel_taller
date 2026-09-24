<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Admin\Factura;
use App\Models\Admin\Notificacion;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    /**
     * Listar facturas
     */
    public function index(Request $request)
    {
        $query = Factura::with(['cliente.usuario', 'orden.vehiculo', 'pagos'])
            ->orderByDesc('fecha');

        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('numero_factura', 'like', "%{$term}%")
                  ->orWhereHas('cliente.usuario', fn($u) => $u->where('nombre', 'like', "%{$term}%"))
                  ->orWhereHas('orden.vehiculo', fn($v) => $v->where('placa', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $facturas = $query->paginate(15);

        return view('tecnico.facturas.index', compact('facturas'));
    }

    /**
     * Formulario para que el empleado genere una factura directamente
     */
    public function create()
    {
        $clientes = Cliente::with('usuario')->get();
        $ordenes = OrdenTrabajo::whereDoesntHave('factura')
            ->with(['vehiculo.cliente.usuario'])
            ->orderByDesc('fecha_ingreso')
            ->get();

        return view('tecnico.facturas.create', compact('clientes', 'ordenes'));
    }

    /**
     * Guardar factura generada por el empleado
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_orden'   => 'nullable|exists:ordenes_trabajo,id_orden',
            'subtotal'   => 'required|numeric|min:0',
        ], [
            'id_cliente.required' => 'Debe seleccionar un cliente para generar la factura.',
            'subtotal.required'   => 'El monto subtotal es requerido.',
        ]);

        $subtotal = (float) $request->subtotal;
        $impuesto = round($subtotal * 0.19, 2);
        $total    = $subtotal + $impuesto;

        $factura = DB::transaction(function () use ($request, $subtotal, $impuesto, $total) {
            $numero = 'F-' . str_pad(Factura::count() + 1, 6, '0', STR_PAD_LEFT);

            $factura = Factura::create([
                'id_cliente'     => $request->id_cliente,
                'id_orden'       => $request->id_orden ?: null,
                'numero_factura' => $numero,
                'fecha'          => now()->toDateString(),
                'subtotal'       => $subtotal,
                'impuesto'       => $impuesto,
                'total'          => $total,
                'estado'         => 'Pendiente',
            ]);

            // Notificar al cliente
            $cliente = Cliente::find($request->id_cliente);
            if ($cliente && $cliente->id_usuario) {
                Notificacion::create([
                    'id_usuario_destinatario' => $cliente->id_usuario,
                    'id_orden'               => $request->id_orden ?: null,
                    'tipo'                   => 'factura_generada',
                    'titulo'                 => "Factura Generada #{$numero}",
                    'descripcion'            => "Se ha generado la factura {$numero} por un total de $" . number_format($total, 2) . ". Ya puedes revisarla y descargarla en tu portal de cliente.",
                    'leida'                  => false,
                ]);
            }

            return $factura;
        });

        return redirect()->route('tecnico.facturas.show', $factura)
            ->with('success', "Factura {$factura->numero_factura} generada exitosamente para el cliente.");
    }

    /**
     * Ver factura
     */
    public function show(Factura $factura)
    {
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

        return view('tecnico.facturas.show', compact('factura'));
    }

    /**
     * Descargar factura en PDF
     */
    public function pdf(Factura $factura)
    {
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
}
