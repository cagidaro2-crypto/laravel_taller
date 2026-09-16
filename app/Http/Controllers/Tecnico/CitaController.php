<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['cliente.usuario', 'vehiculo'])
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
        $cita->load(['cliente.usuario', 'vehiculo', 'usuario']);
        return view('tecnico.citas.show', compact('cita'));
    }
}
