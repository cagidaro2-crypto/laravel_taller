<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cotizacion;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario  = Auth::user();
        $cliente  = $usuario->cliente;

        // Si el usuario no tiene registro en clientes todavía, mostrar dashboard vacío
        if (!$cliente) {
            return view('cliente.dashboard', [
                'misVehiculos'           => 0,
                'misCitas'               => 0,
                'cotizacionesPendientes' => 0,
            ]);
        }

        $clienteId = $cliente->id_cliente;

        $misVehiculos           = Vehiculo::where('id_cliente', $clienteId)->count();
        $misCitas               = Cita::where('id_cliente', $clienteId)
            ->where('estado', '!=', 'Cancelado')
            ->count();
        $cotizacionesPendientes = Cotizacion::where('id_cliente', $clienteId)
            ->where('estado', 'Pendiente')
            ->count();

        return view('cliente.dashboard', compact('misVehiculos', 'misCitas', 'cotizacionesPendientes'));
    }
}
