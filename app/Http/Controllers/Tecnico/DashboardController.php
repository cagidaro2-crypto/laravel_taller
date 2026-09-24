<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $misOrdenes = OrdenTrabajo::where('id_usuario', Auth::id())
            ->whereHas('estado', fn($q) =>
                $q->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
            )->count();

        // BUG #6 CORRECCIÓN: MEDIO - Dashboard contador de citas sin filtrar
        // Las citas de hoy SOLO deben contar las asignadas al técnico o sin asignar
        $citasHoy = Cita::whereDate('fecha', today())
            ->where('estado', '!=', 'Cancelado')
            ->where(function($q) {
                $q->where('id_usuario', Auth::id())
                  ->orWhereNull('id_usuario');
            })
            ->count();

        $vehiculosEnTaller = Vehiculo::whereHas('ordenesTrabajo', fn($q) =>
            $q->where('id_usuario', Auth::id())
              ->whereHas('estado', fn($q2) =>
                $q2->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
            )
        )->count();

        $ultimasOrdenes = OrdenTrabajo::where('id_usuario', Auth::id())
            ->with(['vehiculo.cliente.usuario', 'estado'])
            ->latest()
            ->take(4)
            ->get();

        $proximasCitas = Cita::whereDate('fecha', '>=', today())
            ->where('estado', '!=', 'Cancelado')
            ->where(function($q) {
                $q->where('id_usuario', Auth::id())
                  ->orWhereNull('id_usuario');
            })
            ->with(['cliente.usuario', 'vehiculo'])
            ->orderBy('fecha')
            ->take(3)
            ->get();

        return view('tecnico.dashboard', compact(
            'misOrdenes',
            'citasHoy',
            'vehiculosEnTaller',
            'ultimasOrdenes',
            'proximasCitas'
        ));
    }
}
