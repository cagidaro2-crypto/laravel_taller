<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\HistorialVehiculo;
use App\Models\Tecnico\Vehiculo;
use App\Models\Tecnico\OrdenTrabajo;
use Illuminate\Support\Facades\Auth;

class HistorialVehiculoController extends Controller
{
    public function index()
    {
        // BUG #4: Filtrar historial solo para vehículos con órdenes del técnico
        $historial = HistorialVehiculo::with(['vehiculo.cliente.usuario', 'usuario'])
            ->whereHas('vehiculo.ordenesTrabajo', function($q) {
                $q->where('id_usuario', Auth::id());
            })
            ->orderByDesc('fecha')
            ->paginate(20);

        return view('tecnico.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historialVehiculo)
    {
        // BUG #4: Verificar autorización
        abort_if(!$historialVehiculo->vehiculo->ordenesTrabajo()
            ->where('id_usuario', Auth::id())->exists(), 403);
        
        $historialVehiculo->load(['vehiculo.cliente.usuario', 'usuario']);
        
        return view('tecnico.historial.show', compact('historialVehiculo'));
    }
}
