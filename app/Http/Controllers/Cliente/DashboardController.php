<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cotizacion;
use App\Models\Admin\Factura;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $cliente = $usuario->cliente;

        if (!$cliente) {
            $cliente = Cliente::firstOrCreate(
                ['id_usuario' => $usuario->id_usuario],
                [
                    'documento' => 'CLI-' . str_pad($usuario->id_usuario, 5, '0', STR_PAD_LEFT),
                    'direccion' => null,
                ]
            );
        }

        $clienteId = $cliente->id_cliente;

        $misVehiculos           = Vehiculo::where('id_cliente', $clienteId)->count();
        $misCitas               = Cita::where('id_cliente', $clienteId)
            ->where('estado', '!=', 'Cancelado')
            ->count();
        $cotizacionesPendientes = Cotizacion::where('id_cliente', $clienteId)
            ->where('estado', 'Pendiente')
            ->count();
        $misFacturas            = Factura::where('id_cliente', $clienteId)->count();
        $facturasPendientes     = Factura::where('id_cliente', $clienteId)
            ->where('estado', 'Pendiente')
            ->count();

        $ultimosVehiculos = Vehiculo::where('id_cliente', $clienteId)->with('estado')->latest()->take(3)->get();
        $ultimasFacturas  = Factura::where('id_cliente', $clienteId)->orderByDesc('fecha')->take(3)->get();
        $proximasCitas    = Cita::where('id_cliente', $clienteId)
            ->where('estado', '!=', 'Cancelado')
            ->orderByDesc('fecha')
            ->take(2)
            ->get();

        return view('cliente.dashboard', compact(
            'misVehiculos',
            'misCitas',
            'cotizacionesPendientes',
            'misFacturas',
            'facturasPendientes',
            'ultimosVehiculos',
            'ultimasFacturas',
            'proximasCitas'
        ));
    }
}
