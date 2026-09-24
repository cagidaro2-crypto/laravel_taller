<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Cita;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    private function clienteId(): int
    {
        return Auth::user()->cliente->id_cliente;
    }

    public function index()
    {
        $citas = Cita::where('id_cliente', $this->clienteId())
            ->with('vehiculo')
            ->orderByDesc('fecha')
            ->get();

        return view('cliente.citas.index', compact('citas'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::where('id_cliente', $this->clienteId())->get();

        if ($vehiculos->isEmpty()) {
            return redirect()->route('cliente.vehiculos.index')
                ->with('info', 'Debe registrarse e iniciar sesión antes de agendar una cita.');
        }

        return view('cliente.citas.create', compact('vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_vehiculo' => 'required|exists:vehiculos,id_vehiculo',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora'        => 'required',
            'motivo'      => 'nullable|string|max:255',
        ], [
            'id_vehiculo.required' => 'Debe completar todos los campos obligatorios.',
            'fecha.required'       => 'Debe completar todos los campos obligatorios.',
            'hora.required'        => 'Debe completar todos los campos obligatorios.',
        ]);

        // BUG #12 CORRECCIÓN: Validar que el vehículo pertenezca al cliente
        $vehiculo = Vehiculo::where('id_vehiculo', $request->id_vehiculo)
            ->where('id_cliente', $this->clienteId())
            ->firstOrFail();

        // TDLP-020 escenario 2: horario ocupado
        $ocupado = Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->whereNotIn('estado', ['Cancelado'])
            ->exists();

        if ($ocupado) {
            return back()->withInput()->with('error', 'El horario seleccionado no está disponible. Por favor elige otro horario.');
        }

        $referencia = 'CIT-' . strtoupper(uniqid());

        $cita = Cita::create([
            'id_cliente'   => $this->clienteId(),
            'id_vehiculo'  => $request->id_vehiculo,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
            'motivo'       => $request->motivo,
            'estado'       => 'Pendiente',
            'observaciones'=> $referencia,
        ]);

        return redirect()->route('cliente.citas.index')
            ->with('success', "Cita agendada exitosamente para el {$cita->fecha->format('d/m/Y')} a las {$cita->hora}. Número de referencia: {$referencia}.");
    }

    public function show(Cita $cita)
    {
        abort_if($cita->id_cliente !== $this->clienteId(), 403);
        $cita->load(['vehiculo', 'usuario']);
        return view('cliente.citas.show', compact('cita'));
    }

    // TDLP-020 escenario 3: cancelar cita
    public function cancelar(Cita $cita)
    {
        abort_if($cita->id_cliente !== $this->clienteId(), 403);

        $cita->update(['estado' => 'Cancelado']);

        return redirect()->route('cliente.citas.index')
            ->with('success', 'Tu cita ha sido cancelada. El taller ha sido notificado del cambio.');
    }
}
