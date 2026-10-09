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
            ->where(function($q) {
                $q->whereHas('vehiculo.ordenesTrabajo', function($sq) {
                    $sq->where('id_usuario', Auth::id());
                })->orWhere('id_usuario', Auth::id());
            })
            ->orderByDesc('fecha')
            ->paginate(20);

        return view('tecnico.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historialVehiculo)
    {
        $autorizado = $historialVehiculo->id_usuario === Auth::id() || 
            ($historialVehiculo->vehiculo && $historialVehiculo->vehiculo->ordenesTrabajo()->where('id_usuario', Auth::id())->exists());
        abort_if(!$autorizado, 403);
        
        $historialVehiculo->load(['vehiculo.cliente.usuario', 'usuario']);
        
        return view('tecnico.historial.show', compact('historialVehiculo'));
    }
}
