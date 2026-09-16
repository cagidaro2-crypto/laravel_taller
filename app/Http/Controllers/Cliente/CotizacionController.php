<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    private function clienteId(): int
    {
        return Auth::user()->cliente->id_cliente;
    }

    // RF-43: Ver cotizaciones del cliente
    public function index()
    {
        $cotizaciones = Cotizacion::where('id_cliente', $this->clienteId())
            ->orderByDesc('fecha')
            ->get();

        // RF-43: Si no tiene cotizaciones pendientes
        $pendientes = $cotizaciones->where('estado', 'Pendiente')->count();

        return view('cliente.cotizaciones.index', compact('cotizaciones', 'pendientes'));
    }

    public function show(Cotizacion $cotizacione)
    {
        abort_if($cotizacione->id_cliente !== $this->clienteId(), 403);
        $cotizacione->load(['servicios.servicio', 'productos.producto', 'vehiculo']);
        return view('cliente.cotizaciones.show', compact('cotizacione'));
    }

    // RF-44: Aprobar cotización
    public function aprobar(Cotizacion $cotizacione)
    {
        abort_if($cotizacione->id_cliente !== $this->clienteId(), 403);

        // RF-45: Verificar que no esté vencida
        if ($cotizacione->fecha_vencimiento && $cotizacione->fecha_vencimiento->isPast()) {
            return back()->with('error', 'Esta cotización ha expirado. Solicite una nueva cotización al administrador.');
        }

        if ($cotizacione->estado !== 'Pendiente') {
            return back()->with('error', 'Esta cotización ya fue procesada.');
        }

        $cotizacione->update(['estado' => 'Aprobada']);

        return redirect()->route('cliente.cotizaciones.show', $cotizacione)
            ->with('success', 'Cotización aprobada. El taller ha sido notificado.');
    }

    // RF-44: Rechazar cotización
    public function rechazar(Request $request, Cotizacion $cotizacione)
    {
        abort_if($cotizacione->id_cliente !== $this->clienteId(), 403);

        if ($cotizacione->estado !== 'Pendiente') {
            return back()->with('error', 'Esta cotización ya fue procesada.');
        }

        $cotizacione->update(['estado' => 'Rechazada']);

        return redirect()->route('cliente.cotizaciones.index')
            ->with('success', 'Cotización rechazada. El taller ha sido notificado del cambio.');
    }
}
