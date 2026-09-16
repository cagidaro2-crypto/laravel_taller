<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Inventario;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\OrdenTrabajo;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrdenes  = OrdenTrabajo::whereHas('estado', fn($q) =>
            $q->whereNotIn('nombre', ['Terminado', 'Entregado', 'Cancelado'])
        )->count();
        $totalClientes = Cliente::count();
        $bajosStock    = Inventario::whereRaw('cantidad <= stock_minimo')->count();
        $citasHoy      = Cita::whereDate('fecha', today())
            ->where('estado', '!=', 'Cancelado')
            ->count();

        return view('admin.dashboard', compact('totalOrdenes', 'totalClientes', 'bajosStock', 'citasHoy'));
    }
}
