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
}
