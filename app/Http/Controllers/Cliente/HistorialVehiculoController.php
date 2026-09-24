<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\HistorialVehiculo;

class HistorialVehiculoController extends Controller
{
    use HasClienteProfile;

    public function index()
    {
        $historial = HistorialVehiculo::whereHas('vehiculo', function ($q) {
            $q->where('id_cliente', $this->clienteId());
        })
        ->with(['vehiculo.fotos', 'usuario'])
        ->orderByDesc('fecha')
        ->get();

        return view('cliente.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historial)
    {
        abort_if(!$historial->vehiculo || $historial->vehiculo->id_cliente !== $this->clienteId(), 403);
        $historial->load(['vehiculo.fotos', 'usuario']);
        $historialVehiculo = $historial;

        return view('cliente.historial.show', compact('historial', 'historialVehiculo'));
    }
}
