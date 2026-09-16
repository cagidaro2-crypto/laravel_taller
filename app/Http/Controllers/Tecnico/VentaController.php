<?php

namespace App\Http\Controllers\Tecnico;

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
    public function index()
    {
        $ventas = Venta::where('id_usuario', Auth::id())
            ->with(['cliente.usuario', 'detalles'])
            ->orderByDesc('fecha')
            ->paginate(15);

        return view('tecnico.ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes  = Cliente::with('usuario')->get();
        $productos = Producto::where('activo', true)->with('inventario')->get();
        return view('tecnico.ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'          => 'required|exists:clientes,id_cliente',
            'items'               => 'required|array|min:1',
            'items.*.id_producto' => 'required|exists:productos,id_producto',
            'items.*.cantidad'    => 'required|integer|min:1',
        ], [
            'id_cliente.required' => 'Complete todos los campos obligatorios para registrar la venta.',
        ]);

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

            $venta = Venta::create([
                'id_cliente' => $request->id_cliente,
                'id_usuario' => Auth::id(),
                'fecha'      => now()->toDateString(),
                'subtotal'   => $subtotal,
                'impuesto'   => $impuesto,
                'total'      => $subtotal + $impuesto,
                'estado'     => 'Completada',
            ]);

            foreach ($request->items as $item) {
                $prod = Producto::find($item['id_producto']);
                DetalleVenta::create([
                    'id_venta'        => $venta->id_venta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $prod->precio_venta,
                    'subtotal'        => $prod->precio_venta * $item['cantidad'],
                ]);

                $inv = Inventario::where('id_producto', $item['id_producto'])->first();
                $inv->decrement('cantidad', $item['cantidad']);
                $inv->update(['ultima_actualizacion' => now()]);
            }
        });

        return redirect()->route('tecnico.ventas.index')
            ->with('success', 'Venta registrada exitosamente.');
    }

    public function show(Venta $venta)
    {
        abort_if($venta->id_usuario !== Auth::id(), 403);
        $venta->load(['cliente.usuario', 'detalles.producto']);
        return view('tecnico.ventas.show', compact('venta'));
    }
}
