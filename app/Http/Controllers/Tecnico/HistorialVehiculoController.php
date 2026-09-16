<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\HistorialVehiculo;

class HistorialVehiculoController extends Controller
{
    public function index()
    {
        $historial = HistorialVehiculo::with('vehiculo')->get();
        return view('tecnico.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historialVehiculo)
    {
        return view('tecnico.historial.show', compact('historialVehiculo'));
    }
}
