<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $usuarioId = Auth::id();
        
        // BUG #4 CORRECCIÓN: Filtrar citas solo del técnico asignado
        $query = Cita::with(['cliente.usuario', 'vehiculo', 'usuario'])
            ->where(function($q) use ($usuarioId) {
                $q->where('id_usuario', $usuarioId)  // Citas asignadas al técnico
                  ->orWhereNull('id_usuario');        // O citas sin asignar
            })
            ->orderBy('fecha')
            ->orderBy('hora');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $citas = $query->paginate(15);

        return view('tecnico.citas.index', compact('citas'));
    }

    public function show(Cita $cita)
    {
        // Validar que la cita pertenezca al técnico o esté sin asignar
        if ($cita->id_usuario !== null && $cita->id_usuario !== Auth::id()) {
            abort(403, 'No autorizado para ver esta cita');
        }

        $cita->load(['cliente.usuario', 'vehiculo', 'usuario']);
        return view('tecnico.citas.show', compact('cita'));
    }
}
