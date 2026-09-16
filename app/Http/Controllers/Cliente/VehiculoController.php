<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehiculoController extends Controller
{
    private function clienteId(): int
    {
        return Auth::user()->cliente->id_cliente;
    }

    public function index()
    {
        $vehiculos = Vehiculo::where('id_cliente', $this->clienteId())
            ->with('estado')
            ->get();

        if ($vehiculos->isEmpty()) {
            return view('cliente.vehiculos.index', ['vehiculos' => $vehiculos])
                ->with('info', 'No tienes vehículos registrados. Comunícate con el taller para registrar tu vehículo.');
        }

        return view('cliente.vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        return view('cliente.vehiculos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa'  => 'required|string|max:20|unique:vehiculos,placa|regex:/^[A-Z]{3}-[0-9]{3}$/',
            'marca'  => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'anio'   => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'color'  => 'nullable|string|max:50',
        ], [
            'placa.required' => 'Debe completar todos los campos obligatorios.',
            'placa.unique'   => 'La placa ingresada ya está registrada en el sistema.',
            'placa.regex'    => 'El formato de la placa no es válido. Use el formato: ABC-123.',
            'marca.required' => 'Debe completar todos los campos obligatorios.',
            'modelo.required'=> 'Debe completar todos los campos obligatorios.',
        ]);

        // Estado por defecto — id del estado "Ingresado" o similar
        $estadoDefault = \App\Models\Tecnico\EstadoVehiculo::first();

        Vehiculo::create([
            'id_cliente' => $this->clienteId(),
            'id_estado'  => $estadoDefault->id_estado,
            'placa'      => strtoupper($request->placa),
            'marca'      => $request->marca,
            'modelo'     => $request->modelo,
            'anio'       => $request->anio,
            'color'      => $request->color,
            'tipo'       => $request->tipo,
            'vin'        => $request->vin,
        ]);

        return redirect()->route('cliente.vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente.');
    }

    public function show(Vehiculo $vehiculo)
    {
        // Solo el dueño puede ver su vehículo
        abort_if($vehiculo->id_cliente !== $this->clienteId(), 403);

        $vehiculo->load(['estado', 'fotos', 'historial', 'ordenesTrabajo.estado']);

        return view('cliente.vehiculos.show', compact('vehiculo'));
    }

    // TDLP-009: Subir foto
    public function subirFoto(Request $request, Vehiculo $vehiculo)
    {
        abort_if($vehiculo->id_cliente !== $this->clienteId(), 403);

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'foto.mimes' => 'El formato del archivo no es válido. Se aceptan únicamente: JPG, PNG, JPEG.',
            'foto.max'   => 'El archivo supera el tamaño máximo permitido de 10 MB.',
        ]);

        $path = $request->file('foto')->store('vehiculos', 'public');
        $vehiculo->fotos()->create(['ruta_foto' => $path, 'descripcion' => $request->descripcion]);

        return back()->with('success', 'Fotografía subida exitosamente.');
    }
}
