<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Tecnico\ConsumoMaterial;
use App\Models\Admin\Producto;
use App\Models\Admin\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsumoMaterialController extends Controller
{
    // Ver orden con consumo de materiales
    public function show(OrdenTrabajo $ordene)
    {
        // Verificar que la orden pertenezca al técnico
        abort_if($ordene->id_usuario !== Auth::id(), 403);

        $ordene->load([
            'vehiculo.cliente.usuario',
            'estado',
            'servicios.servicio',
            'productos.producto',
            'consumoMateriales.producto.inventario'
        ]);

        $productos = Producto::where('activo', true)
            ->with('inventario')
            ->get();

        return view('tecnico.ordenes.detalles_materiales', compact('ordene', 'productos'));
    }

    // Agregar material consumido
    public function store(Request $request, OrdenTrabajo $ordene)
    {
        // Verificar que la orden pertenezca al técnico
        abort_if($ordene->id_usuario !== Auth::id(), 403);

        $request->validate([
            'id_producto'    => 'required|exists:productos,id_producto',
            'cantidad_usada' => 'required|integer|min:1|max:10000',
            'observaciones'  => 'nullable|string|max:500',
        ]);

        // Obtener producto
        $producto = Producto::findOrFail($request->id_producto);

        // BUG #5: Validar que producto esté activo
        if (!$producto->activo) {
            return back()->withInput()->with('error', 
                "El producto '{$producto->nombre}' no está activo y no puede ser consumido.");
        }

        // Verificar stock disponible
        $inventario = Inventario::where('id_producto', $request->id_producto)->first();

        if (!$inventario || $inventario->cantidad < $request->cantidad_usada) {
            return back()->with('error', 
                "Stock insuficiente para '{$producto->nombre}'. Disponible: " . ($inventario->cantidad ?? 0)
            );
        }

        // Usar transacción para consistencia
        DB::transaction(function () use ($request, $producto, $inventario, $ordene) {
            $subtotal = $producto->precio_venta * $request->cantidad_usada;

            // Registrar consumo
            ConsumoMaterial::create([
                'id_orden'          => $ordene->id_orden,
                'id_producto'       => $request->id_producto,
                'cantidad_usada'    => $request->cantidad_usada,
                'precio_unitario'   => $producto->precio_venta,
                'subtotal'          => $subtotal,
                'observaciones'     => $request->observaciones,
                'fecha_consumo'     => now(),
            ]);

            // Restar del inventario
            $inventario->decrement('cantidad', $request->cantidad_usada);
            $inventario->update(['ultima_actualizacion' => now()]);

            // Actualizar totales de la orden
            $this->actualizarTotalesOrden($ordene);
        });

        return back()->with('success', "Consumo de '{$producto->nombre}' registrado y restado del inventario.");
    }

    // Eliminar material consumido
    public function destroy(ConsumoMaterial $consumo)
    {
        $ordene = $consumo->orden;

        // Verificar que la orden pertenezca al técnico
        abort_if($ordene->id_usuario !== Auth::id(), 403);

        DB::transaction(function () use ($consumo, $ordene) {
            $inventario = Inventario::where('id_producto', $consumo->id_producto)->first();

            // Devolver al inventario
            if ($inventario) {
                $inventario->increment('cantidad', $consumo->cantidad_usada);
                $inventario->update(['ultima_actualizacion' => now()]);
            }

            // Eliminar consumo
            $consumo->delete();

            // Actualizar totales
            $this->actualizarTotalesOrden($ordene);
        });

        return back()->with('success', 'Consumo eliminado y stock devuelto al inventario.');
    }

    // Actualizar totales de la orden
    private function actualizarTotalesOrden(OrdenTrabajo $ordene)
    {
        $ordene->load('consumoMateriales', 'servicios', 'productos');

        $totalServicios = $ordene->servicios->sum('valor_unitario') ?? 0;
        $totalProductos = ($ordene->productos->sum('subtotal') ?? 0);
        $totalMateriales = $ordene->consumoMateriales->sum('subtotal') ?? 0;

        $subtotal = $totalServicios + $totalProductos + $totalMateriales;
        $impuesto = $subtotal * 0.19;
        $total = $subtotal + $impuesto;

        $ordene->update([
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total'    => $total,
        ]);
    }
}
