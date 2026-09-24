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
        // CORRECCIÓN: Técnico ve TODOS los historiales
        // Ya que puede registrar servicios en cualquier vehículo asignado a él
        $historial = HistorialVehiculo::with(['vehiculo.cliente.usuario', 'usuario'])
            ->orderByDesc('fecha')
            ->paginate(20);

        return view('tecnico.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historialVehiculo)
    {
        $historialVehiculo->load(['vehiculo.cliente.usuario', 'usuario']);
        
        return view('tecnico.historial.show', compact('historialVehiculo'));
    }
}
