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

        $citasHoy = Cita::whereDate('fecha', today())
            ->where('estado', '!=', 'Cancelado')
            ->count();

        $vehiculosEnTaller = Vehiculo::whereHas('ordenesTrabajo', fn($q) =>
            $q->whereHas('estado', fn($q2) =>
                $q2->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
            )
        )->count();

        return view('tecnico.dashboard', compact('misOrdenes', 'citasHoy', 'vehiculosEnTaller'));
    }
}
