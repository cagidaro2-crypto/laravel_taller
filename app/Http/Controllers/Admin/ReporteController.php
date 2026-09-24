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
            ->selectRaw('id_producto, SUM(cantidad) as total_cantidad, SUM(subtotal) as total_subtotal')
            ->groupBy('id_producto')
            ->get();

        $sinDatos = $consumo->isEmpty();

        return view('admin.reportes.consumo', compact('consumo', 'sinDatos', 'request'));
    }

    // RF-87: Exportar reportes
    public function exportarCSV(Request $request)
    {
        $tipo = $request->input('tipo', 'productividad');
        
        $filename = 'reporte_' . $tipo . '_' . now()->format('Y-m-d_Hi') . '.csv';
        
        if ($tipo === 'productividad') {
            $headers = ['Técnico', 'Órdenes Período', 'Órdenes Completadas', 'Porcentaje'];
            $data = [];
            
            $tecnicos = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))
                ->withCount(['ordenesTrabajo as ordenes_periodo' => fn($q) =>
                    $q->whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
                ])
                ->get();
                
            foreach ($tecnicos as $tecnico) {
                $completadas = OrdenTrabajo::where('id_usuario', $tecnico->id_usuario)
                    ->whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
                    ->whereHas('estado', fn($q) => $q->whereIn('nombre', ['Finalizado', 'Entregado']))
                    ->count();
                    
                $porcentaje = $tecnico->ordenes_periodo > 0 ? round(($completadas / $tecnico->ordenes_periodo) * 100, 2) : 0;
                
                $data[] = [
                    $tecnico->nombre,
                    $tecnico->ordenes_periodo,
                    $completadas,
                    $porcentaje . '%'
                ];
            }
        } elseif ($tipo === 'ingresos') {
            $headers = ['Fecha', 'Cliente', 'Total', 'Impuesto', 'Neto'];
            $data = [];
            
            $ventas = Venta::whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta])
                ->where('estado', 'Completada')
                ->with(['detalles.producto', 'cliente.usuario'])
                ->get();
                
            foreach ($ventas as $venta) {
                $data[] = [
                    $venta->fecha->format('d/m/Y'),
                    $venta->cliente->usuario->nombre ?? 'N/A',
                    number_format($venta->total, 2),
                    number_format($venta->impuesto, 2),
                    number_format($venta->total - $venta->impuesto, 2)
                ];
            }
        } else {
            $headers = ['Producto', 'Cantidad Usada', 'Subtotal'];
            $data = [];
            
            $consumo = OrdenProducto::with(['producto.categoria', 'orden'])
                ->whereHas('orden', fn($q) =>
                    $q->whereBetween('fecha_ingreso', [$request->fecha_desde, $request->fecha_hasta])
                )
                ->selectRaw('id_producto, SUM(cantidad) as total_cantidad, SUM(subtotal) as total_subtotal')
                ->groupBy('id_producto')
                ->get();
                
            foreach ($consumo as $item) {
                $data[] = [
                    $item->producto->nombre,
                    $item->total_cantidad,
                    number_format($item->total_subtotal, 2)
                ];
            }
        }
        
        // Crear CSV
        // BUG #11 CORRECCIÓN: Agregar BOM UTF-8 para que Excel lea caracteres correctamente
        $csv = fopen('php://memory', 'r+');
        fprintf($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));  // UTF-8 BOM
        fputcsv($csv, $headers);
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);
        
        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
