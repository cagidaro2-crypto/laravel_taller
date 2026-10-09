<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\HistorialVehiculo;
use App\Models\Tecnico\Vehiculo;

class HistorialVehiculoController extends Controller
{
    use HasClienteProfile;

    public function index()
    {
        // Sincronizar compras de todos los vehículos del cliente en tiempo real
        $vehiculos = Vehiculo::where('id_cliente', $this->clienteId())->get();
        foreach ($vehiculos as $v) {
            $v->syncCompras();
        }

        $historial = HistorialVehiculo::whereHas('vehiculo', function ($q) {
            $q->where('id_cliente', $this->clienteId());
        })
        ->with(['vehiculo.fotos', 'vehiculo.estado', 'usuario'])
        ->orderByDesc('fecha')
        ->orderByDesc('id_historial')
        ->get();

        return view('cliente.historial.index', compact('historial'));
    }

    public function show(HistorialVehiculo $historial)
    {
        abort_if(!$historial->vehiculo || $historial->vehiculo->id_cliente !== $this->clienteId(), 403);

        $historial->vehiculo->syncCompras();
        $historial->refresh();
        $historial->load(['vehiculo.fotos', 'vehiculo.estado', 'usuario']);
        $historialVehiculo = $historial;

        return view('cliente.historial.show', compact('historial', 'historialVehiculo'));
    }
}
