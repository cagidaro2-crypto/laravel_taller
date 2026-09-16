<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DetalleVenta;
use App\Models\Admin\Inventario;
use App\Models\Tecnico\OrdenProducto;
use App\Models\Admin\Venta;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }

    // RF-84: Reporte de productividad
    public function productividad(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $tecnicos = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))
            ->withCount(['ordenesTrabajo as ordenes_periodo' => fn($q) =>
                $q->whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
            ])
            ->get();

        $ordenesCompletadas = OrdenTrabajo::whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
            ->whereHas('estado', fn($q) => $q->whereIn('nombre', ['Finalizado', 'Entregado']))
            ->count();

        $sinDatos = $tecnicos->sum('ordenes_periodo') === 0;

        return view('admin.reportes.productividad', compact('tecnicos', 'ordenesCompletadas', 'sinDatos', 'request'));
    }

    // RF-85: Reporte de ingresos
    public function ingresos(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $ventas = Venta::whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta])
            ->where('estado', 'Completada')
            ->with(['detalles.producto', 'cliente.usuario'])
            ->get();

        $totalIngresos = $ventas->sum('total');
        $totalImpuesto = $ventas->sum('impuesto');

        $sinDatos = $ventas->isEmpty();

        return view('admin.reportes.ingresos', compact('ventas', 'totalIngresos', 'totalImpuesto', 'sinDatos', 'request'));
    }

    // RF-86: Reporte de consumo de materiales
    public function consumo(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $consumo = OrdenProducto::with(['producto.categoria', 'orden'])
            ->whereHas('orden', fn($q) =>
                $q->whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
            )
            ->select('id_producto', DB::raw('SUM(cantidad) as total_cantidad'), DB::raw('SUM(subtotal) as total_subtotal'))
            ->groupBy('id_producto')
            ->with('producto.categoria')
            ->get();

        $sinDatos = $consumo->isEmpty();

        return view('admin.reportes.consumo', compact('consumo', 'sinDatos', 'request'));
    }
}
