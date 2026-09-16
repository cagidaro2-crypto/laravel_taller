<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\HistorialVehiculo;

class HistorialVehiculoController extends Controller
{
    public function index()
    {
        $historial = HistorialVehiculo::whereHas('vehiculo.cliente', function ($q) {
            $q->where('id_usuario', auth()->id());
        })->with('vehiculo')->get();
        return view('cliente.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historialVehiculo)
    {
        return view('cliente.historial.show', compact('historialVehiculo'));
    }
}
