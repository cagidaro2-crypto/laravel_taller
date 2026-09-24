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
        // BUG #7 CORRECCIÓN: BAJO - Actualización redundante
        // Solo actualizar campos que se proporcionan explícitamente
        abort_if($ordene->id_usuario !== Auth::id(), 403, 'No autorizado para actualizar esta orden');

        $request->validate([
            'id_estado'    => 'required|exists:estados_ot,id_estado',
            'observaciones'=> 'nullable|string',
        ]);

        $dataToUpdate = ['id_estado' => $request->id_estado];
        
        // Solo actualizar observaciones si se proporciona
        if ($request->filled('observaciones')) {
            $dataToUpdate['observaciones'] = $request->observaciones;
        }

        $ordene->update($dataToUpdate);

        return back()->with('success', 'Estado de la orden actualizado correctamente.');
    }
}
