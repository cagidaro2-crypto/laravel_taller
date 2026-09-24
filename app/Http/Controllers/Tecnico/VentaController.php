<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Admin\DetalleVenta;
use App\Models\Admin\Inventario;
use App\Models\Admin\Producto;
use App\Models\Admin\Venta;
use App\Models\Admin\Usuario;
use App\Models\Cliente\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    // Constante IVA (BUG #3: Centralizar IVA)
    const IVA_RATE = 0.19;

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
        // BUG #9: Validar que cliente esté activo
        $clientes  = Cliente::whereHas('usuario', function ($q) {
            $q->where('activo', true);
        })->with('usuario')->get();
        
        $productos = Producto::where('activo', true)->with('inventario')->get();
        $vehiculos = []; // Inicialmente vacío, se llenará con AJAX
        
        return view('tecnico.ventas.create', compact('clientes', 'productos', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'          => 'required|exists:clientes,id_cliente',
            'id_vehiculo'         => 'nullable|exists:vehiculos,id_vehiculo',
            'fecha'               => 'required|date|date_format:Y-m-d',
            'items'               => 'required|array|min:1',
            'items.*.id_producto' => 'required|integer|exists:productos,id_producto',
            'items.*.cantidad'    => 'required|integer|min:1|max:10000',
        ], [
            'id_cliente.required' => 'Complete todos los campos obligatorios para registrar la venta.',
            'fecha.required'      => 'La fecha es obligatoria.',
            'items.required'      => 'Debe agregar al menos un producto.',
        ]);

        try {
            // BUG #6: Validar que no haya productos duplicados
            $productosIds = collect($request->items)->pluck('id_producto');
            if ($productosIds->count() !== $productosIds->unique()->count()) {
                return back()->withInput()->with('error', 'No puede agregar el mismo producto múltiples veces.');
            }

            // BUG #9: Validar cliente activo
            $cliente = Cliente::with('usuario')->findOrFail($request->id_cliente);
            if (!$cliente->usuario->activo) {
                return back()->withInput()->with('error', 'El cliente no está activo y no puede recibir ventas.');
            }

            // BUG #4 y #7: Optimizar validación de productos - UNA SOLA QUERY
            $productosIds = collect($request->items)->pluck('id_producto')->unique();
            $productos = Producto::whereIn('id_producto', $productosIds)
                ->where('activo', true)
                ->get()
                ->keyBy('id_producto');

            // Verificar que todos los productos solicitados existen y están activos
            foreach ($request->items as $item) {
                if (!isset($productos[$item['id_producto']])) {
                    return back()->withInput()->with('error', 'Uno o más productos no están disponibles.');
                }

                // BUG #7: Validar que precio sea positivo
                $producto = $productos[$item['id_producto']];
                if ($producto->precio_venta <= 0) {
                    return back()->withInput()->with('error', 
                        "El producto \"{$producto->nombre}\" tiene un precio inválido.");
                }
            }

            // BUG #8: Validar stock CON LOCK para evitar race condition
            $inventarios = Inventario::whereIn('id_producto', $productosIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_producto');

            foreach ($request->items as $item) {
                $inv = $inventarios[$item['id_producto']] ?? null;
                if (!$inv || $inv->cantidad < $item['cantidad']) {
                    $prod = $productos[$item['id_producto']];
                    return back()->withInput()->with('error',
                        "Stock insuficiente de \"{$prod->nombre}\". Disponible: " . ($inv->cantidad ?? 0) . '.'
                    );
                }
            }

            // BUG #13: Try-catch en transaction
            DB::transaction(function () use ($request, $productos, $inventarios, $cliente) {
                $subtotal = 0;

                // Calcular subtotal
                foreach ($request->items as $item) {
                    $prod      = $productos[$item['id_producto']];
                    // BUG #6, #7: Validar precio y mejorar precisión decimal
                    if ($prod->precio_venta === null || $prod->precio_venta <= 0) {
                        throw new \Exception("Precio inválido para {$prod->nombre}");
                    }
                    $subtotal = round($subtotal + ($prod->precio_venta * $item['cantidad']), 2);
                }

                // BUG #3: Usar constante IVA
                $impuesto = round($subtotal * self::IVA_RATE, 2);
                $total    = round($subtotal + $impuesto, 2);

                // Crear venta
                $venta = Venta::create([
                    'id_cliente' => $request->id_cliente,
                    'id_usuario' => Auth::id(),
                    'fecha'      => $request->fecha,
                    'subtotal'   => $subtotal,
                    'impuesto'   => $impuesto,
                    'total'      => $total,
                    'estado'     => 'Completada',
                ]);

                // Crear detalles y descontar inventario
                foreach ($request->items as $item) {
                    $prod = $productos[$item['id_producto']];
                    
                    // Crear detalle de venta
                    DetalleVenta::create([
                        'id_venta'        => $venta->id_venta,
                        'id_producto'     => $item['id_producto'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $prod->precio_venta,
                        'subtotal'        => $prod->precio_venta * $item['cantidad'],
                    ]);

                    // Descontar del inventario
                    $inv = $inventarios[$item['id_producto']];
                    $inv->decrement('cantidad', $item['cantidad']);
                    $inv->update(['ultima_actualizacion' => now()]);

                    // BUG #10: Registrar auditoría de inventario
                    $this->registrarAuditoriaInventario(
                        $item['id_producto'],
                        $item['cantidad'],
                        'venta',
                        $venta->id_venta,
                        Auth::id()
                    );

                    // BUG #11: Alertar si stock queda bajo
                    if ($inv->tieneStockBajo()) {
                        Log::warning("Stock bajo para producto {$prod->nombre} (ID: {$item['id_producto']})", [
                            'stock_actual'  => $inv->cantidad,
                            'stock_minimo'  => $inv->stock_minimo,
                            'id_venta'      => $venta->id_venta,
                        ]);
                    }
                }
            });

            return redirect()->route('tecnico.ventas.index')
                ->with('success', 'Venta registrada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al registrar venta', [
                'error'       => $e->getMessage(),
                'usuario_id'  => Auth::id(),
                'request'     => $request->all(),
            ]);

            return back()->withInput()->with('error', 
                'Error al procesar la venta: ' . $e->getMessage()
            );
        }
    }

    public function show(Venta $venta)
    {
        // BUG #1: Validación de integridad referencial
        abort_if($venta->id_usuario !== Auth::id(), 403, 'No autorizado para ver esta venta');

        // Validar que cliente existe y relaciones están intactas
        if (!$venta->cliente || !$venta->cliente->usuario) {
            Log::warning("Integridad referencial comprometida en venta {$venta->id_venta}");
            return redirect()->route('tecnico.ventas.index')
                ->with('error', 'Error de integridad en los datos de la venta.');
        }

        $venta->load(['cliente.usuario', 'detalles.producto']);
        $usuario = Usuario::findOrFail($venta->id_usuario);

        return view('tecnico.ventas.show', compact('venta', 'usuario'));
    }

    /**
     * BUG #10: Registrar auditoría de cambios en inventario
     */
    private function registrarAuditoriaInventario($idProducto, $cantidad, $tipo, $idReferencia, $idUsuario)
    {
        try {
            DB::table('auditoria_inventario')->insert([
                'id_producto'     => $idProducto,
                'cantidad_cambio' => -$cantidad,
                'tipo_movimiento' => $tipo,
                'id_referencia'   => $idReferencia,
                'id_usuario'      => $idUsuario,
                'descripcion'     => "Venta #{$idReferencia} por técnico",
                'created_at'      => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning("No se pudo registrar auditoría: {$e->getMessage()}");
            // No bloquear la operación si falla la auditoría
        }
    }
}
