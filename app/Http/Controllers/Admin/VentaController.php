<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DetalleVenta;
use App\Models\Admin\Inventario;
use App\Models\Admin\Producto;
use App\Models\Admin\Venta;
use App\Models\Cliente\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // RF-77: Listar ventas con filtros
    public function index(Request $request)
    {
        $query = Venta::with(['cliente.usuario', 'usuario', 'detalles.producto']);

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

        $ventas    = $query->orderByDesc('fecha')->paginate(15);
        $clientes  = Cliente::with('usuario')->get();
        $productos = Producto::where('activo', true)->with('inventario')->get();

        return view('admin.ventas.index', compact('ventas', 'clientes', 'productos'));
    }

    public function create()
    {
        $clientes  = Cliente::with('usuario')->get();
        $productos = Producto::where('activo', true)->with('inventario')->get();
        return view('admin.ventas.create', compact('clientes', 'productos'));
    }

    // RF-74 / RF-75 / RF-76: Registrar venta
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'fecha'      => 'required|date',
            'items'      => 'required|array|min:1',
            'items.*.id_producto' => 'required|exists:productos,id_producto',
            'items.*.cantidad'    => 'required|integer|min:1',
        ], [
            'id_cliente.required' => 'Complete todos los campos obligatorios para registrar la venta.',
            'items.required'      => 'Complete todos los campos obligatorios para registrar la venta.',
        ]);

        // RF-76: Verificar stock antes de registrar
        foreach ($request->items as $item) {
            $inv = Inventario::where('id_producto', $item['id_producto'])->first();
            if (!$inv || $inv->cantidad < $item['cantidad']) {
                $prod = Producto::find($item['id_producto']);
                return back()->withInput()->with('error',
                    "Stock insuficiente para completar la venta. Cantidad disponible de \"{$prod->nombre}\": " . ($inv->cantidad ?? 0) . '.'
                );
            }
        }

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $prod      = Producto::find($item['id_producto']);
                $subtotal += $prod->precio_venta * $item['cantidad'];
            }
            $impuesto = $subtotal * 0.19;
            $total    = $subtotal + $impuesto;

            $venta = Venta::create([
                'id_cliente' => $request->id_cliente,
                'id_usuario' => Auth::id(),
                'fecha'      => $request->fecha,
                'subtotal'   => $subtotal,
                'impuesto'   => $impuesto,
                'total'      => $total,
                'estado'     => 'Completada',
            ]);

            foreach ($request->items as $item) {
                $prod = Producto::find($item['id_producto']);
                DetalleVenta::create([
                    'id_venta'       => $venta->id_venta,
                    'id_producto'    => $item['id_producto'],
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario'=> $prod->precio_venta,
                    'subtotal'       => $prod->precio_venta * $item['cantidad'],
                ]);

                // RF-75: Descontar del inventario
                $inv = Inventario::where('id_producto', $item['id_producto'])->first();
                $inv->decrement('cantidad', $item['cantidad']);
                $inv->update(['ultima_actualizacion' => now()]);
            }
        });

        return redirect()->route('admin.ventas.index')
            ->with('success', 'Venta registrada exitosamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente.usuario', 'usuario', 'detalles.producto']);
        return view('admin.ventas.show', compact('venta'));
    }

    // RF-78: Anular venta
    public function anular(Venta $venta)
    {
        if ($venta->estado === 'Anulada') {
            return back()->with('error', 'Esta venta ya está anulada.');
        }

        DB::transaction(function () use ($venta) {
            // Restaurar stock
            foreach ($venta->detalles as $detalle) {
                $inv = Inventario::where('id_producto', $detalle->id_producto)->first();
                if ($inv) {
                    $inv->increment('cantidad', $detalle->cantidad);
                    $inv->update(['ultima_actualizacion' => now()]);
                }
            }
            $venta->update(['estado' => 'Anulada']);
        });

        return redirect()->route('admin.ventas.show', $venta)
            ->with('success', 'Venta anulada. El stock ha sido restaurado.');
    }
}
