<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Factura;
use App\Models\Admin\Pago;
use App\Models\Cliente\Cliente;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    // RF-62: Listar y filtrar facturas
    public function index(Request $request)
    {
        $query = Factura::with(['cliente.usuario', 'orden', 'pagos']);

        if ($request->filled('cliente')) {
            $query->whereHas('cliente.usuario', fn($q) =>
                $q->where('nombre', 'like', '%'.$request->cliente.'%')
            );
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $facturas = $query->orderByDesc('fecha')->paginate(15);

        return view('admin.facturas.index', compact('facturas'));
    }

    // RF-58: Ver detalle de factura
    public function show(Factura $factura)
    {
        $factura->load(['cliente.usuario', 'orden.servicios.servicio', 'orden.productos.producto', 'pagos']);
        return view('admin.facturas.show', compact('factura'));
    }

    // RF-64: Registrar pago
    public function registrarPago(Request $request, Factura $factura)
    {
        $request->validate([
            'monto'       => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|string|max:50',
            'fecha_pago'  => 'required|date',
            'referencia'  => 'nullable|string|max:100',
        ]);

        Pago::create([
            'id_factura'  => $factura->id_factura,
            'monto'       => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago'  => $request->fecha_pago,
            'referencia'  => $request->referencia,
        ]);

        // Actualizar estado si está completamente pagado
        $totalPagado = $factura->pagos()->sum('monto');
        if ($totalPagado >= $factura->total) {
            $factura->update(['estado' => 'Pagada']);
        }

        return redirect()->route('admin.facturas.show', $factura)
            ->with('success', 'Pago registrado correctamente.');
    }

    // RF-61: Descargar / ver factura como PDF imprimible
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('facturas.pdf_template', compact('factura'));
        return $pdf->download("Factura_{$factura->numero_factura}.pdf");
    }
}
