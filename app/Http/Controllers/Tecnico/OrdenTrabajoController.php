<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Admin\EstadoOt;
use App\Models\Tecnico\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenTrabajoController extends Controller
{
    public function index(Request $request)
    {
        $ordenes = OrdenTrabajo::with(['vehiculo.cliente.usuario', 'estado'])
            ->where('id_usuario', Auth::id())
            ->orderByDesc('fecha_ingreso')
            ->paginate(15);

        return view('tecnico.ordenes.index', compact('ordenes'));
    }

    public function show(OrdenTrabajo $ordene)
    {
        $ordene->load(['vehiculo.cliente.usuario', 'estado', 'servicios.servicio', 'productos.producto']);
        return view('tecnico.ordenes.show', compact('ordene'));
    }

    public function actualizarEstado(Request $request, OrdenTrabajo $ordene)
    {
        $request->validate([
            'id_estado'    => 'required|exists:estados_ot,id_estado',
            'observaciones'=> 'nullable|string',
        ]);

        $ordene->update([
            'id_estado'     => $request->id_estado,
            'observaciones' => $request->observaciones ?? $ordene->observaciones,
        ]);

        return back()->with('success', 'Estado de la orden actualizado correctamente.');
    }
}
