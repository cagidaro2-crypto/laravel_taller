<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\EstadoOt;
use App\Models\Admin\Usuario;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    public function index(Request $request)
    {
        $query = OrdenTrabajo::with(['vehiculo.cliente', 'estado', 'usuario']);

        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        $ordenes  = $query->orderByDesc('fecha_ingreso')->paginate(15);
        $estados  = EstadoOt::all();

        return view('admin.ordenes.index', compact('ordenes', 'estados'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::with('cliente')->get();
        $estados   = EstadoOt::all();
        $tecnicos  = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))->get();

        return view('admin.ordenes.create', compact('vehiculos', 'estados', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_vehiculo'          => 'required|exists:vehiculos,id_vehiculo',
            'id_estado'            => 'required|exists:estados_ot,id_estado',
            'id_usuario'           => 'required|exists:usuarios,id_usuario',
            'fecha_ingreso'        => 'required|date',
            'descripcion_problema' => 'nullable|string',
        ], [
            'id_vehiculo.required' => 'Debe completar todos los campos obligatorios para crear la orden.',
            'id_estado.required'   => 'Debe completar todos los campos obligatorios para crear la orden.',
        ]);

        // TDLP-012 escenario 2: vehículo no registrado ya lo cubre la validación exists
        // TDLP-012 escenario 4: orden activa existente
        $ordenActiva = OrdenTrabajo::where('id_vehiculo', $request->id_vehiculo)
            ->whereHas('estado', fn($q) => $q->whereNotIn('nombre', ['Finalizado', 'Entregado']))
            ->exists();

        if ($ordenActiva) {
            return back()->withInput()->with('error', 'Este vehículo ya tiene una orden de servicio activa. Finalícela antes de crear una nueva.');
        }

        OrdenTrabajo::create($request->only([
            'id_vehiculo', 'id_estado', 'id_usuario',
            'fecha_ingreso', 'fecha_salida',
            'descripcion_problema', 'diagnostico', 'observaciones',
            'subtotal', 'impuesto', 'total',
        ]));

        return redirect()->route('admin.ordenes.index')
            ->with('success', 'Orden de servicio creada exitosamente.');
    }

    public function show(OrdenTrabajo $ordene)
    {
        $ordene->load(['vehiculo.cliente', 'estado', 'usuario', 'servicios.servicio', 'productos.producto', 'factura']);
        return view('admin.ordenes.show', compact('ordene'));
    }

    public function edit(OrdenTrabajo $ordene)
    {
        $vehiculos = Vehiculo::with('cliente')->get();
        $estados   = EstadoOt::all();
        $tecnicos  = Usuario::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Técnico'))->get();

        return view('admin.ordenes.edit', compact('ordene', 'vehiculos', 'estados', 'tecnicos'));
    }

    public function update(Request $request, OrdenTrabajo $ordene)
    {
        $request->validate([
            'id_estado'   => 'required|exists:estados_ot,id_estado',
            'fecha_salida'=> 'nullable|date|after_or_equal:fecha_ingreso',
        ]);

        $ordene->update($request->only([
            'id_estado', 'id_usuario', 'fecha_salida',
            'diagnostico', 'observaciones',
            'subtotal', 'impuesto', 'total',
        ]));

        return redirect()->route('admin.ordenes.show', $ordene)
            ->with('success', 'Orden actualizada correctamente.');
    }

    /**
     * Generar factura a partir de orden de trabajo
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

        $factura = \Illuminate\Support\Facades\DB::transaction(function () use ($ordene, $cliente, $subtotal, $impuesto, $total) {
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
                \App\Models\Admin\Notificacion::create([
                    'id_usuario_destinatario' => $cliente->id_usuario,
                    'id_orden'               => $ordene->id_orden,
                    'id_vehiculo'            => $ordene->id_vehiculo,
                    'tipo'                   => 'factura_generada',
                    'titulo'                 => "Factura Generada #{$numero}",
                    'descripcion'            => "Se ha generado la factura {$numero} por un valor de $" . number_format($total, 2) . " para tu vehículo {$ordene->vehiculo->placa}. Ya puedes consultarla y descargarla en PDF desde tu portal.",
                    'leida'                  => false,
                ]);
            }

            return $factura;
        });

        return redirect()->route('admin.facturas.show', $factura)
            ->with('success', "Factura {$factura->numero_factura} generada exitosamente.");
    }
}
