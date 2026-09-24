<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Vehiculo;
use App\Models\Tecnico\EstadoVehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehiculo::with(['cliente.usuario', 'estado', 'ordenesTrabajo', 'ventas']);

        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }

        if ($request->filled('cliente')) {
            $query->whereHas('cliente.usuario', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        $vehiculos = $query->paginate(15);
        $estados = EstadoVehiculo::all();

        return view('admin.vehiculos.index', compact('vehiculos', 'estados'));
    }

    public function show(Vehiculo $vehiculo)
    {
        $vehiculo->load(['cliente.usuario', 'estado', 'fotos', 'ordenesTrabajo.estado', 'ventas.usuario', 'ventas.detalles', 'historial.usuario']);
        
        return view('admin.vehiculos.show', compact('vehiculo'));
    }
}
