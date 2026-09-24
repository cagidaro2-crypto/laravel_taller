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
            'fotos'  => 'nullable|array',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'placa.required' => 'Debe completar todos los campos obligatorios.',
            'placa.unique'   => 'La placa ingresada ya está registrada en el sistema.',
            'placa.regex'    => 'El formato de la placa no es válido. Use el formato: ABC-123.',
            'marca.required' => 'Debe completar todos los campos obligatorios.',
            'modelo.required'=> 'Debe completar todos los campos obligatorios.',
            'fotos.*.mimes'  => 'El formato del archivo no es válido. Se aceptan únicamente: JPG, PNG, JPEG.',
            'fotos.*.max'    => 'El archivo supera el tamaño máximo permitido de 10 MB.',
        ]);

        // Estado por defecto — id del estado "Ingresado" o similar
        $estadoDefault = \App\Models\Tecnico\EstadoVehiculo::first();

        if (!$estadoDefault) {
            return redirect()->back()
                ->withErrors(['error' => 'No hay estados de vehículo configurados en el sistema. Comunícate con el administrador.']);
        }

        $vehiculo = Vehiculo::create([
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

        // Procesar y guardar fotos si las hay
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('vehiculos', 'public');
                $vehiculo->fotos()->create([
                    'ruta_foto' => $path,
                    'descripcion' => null,
                ]);
            }
        }

        return redirect()->route('cliente.vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente.');
    }

    public function show(Vehiculo $vehiculo)
    {
        // Solo el dueño puede ver su vehículo
        abort_if($vehiculo->id_cliente !== $this->clienteId(), 403);

        $vehiculo->load(['estado', 'fotos', 'historial', 'ordenesTrabajo.estado', 'ventas.usuario', 'ventas.detalles']);

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

        // Recargar la colección de fotos
        $vehiculo->load('fotos');

        return back()->with('success', 'Fotografía subida exitosamente.');
    }

    public function edit(Vehiculo $vehiculo)
    {
        // Solo el dueño puede editar su vehículo
        abort_if($vehiculo->id_cliente !== $this->clienteId(), 403);

        $vehiculo->load('fotos');

        return view('cliente.vehiculos.edit', compact('vehiculo'));
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        // Solo el dueño puede actualizar su vehículo
        abort_if($vehiculo->id_cliente !== $this->clienteId(), 403);

        $request->validate([
            'placa'  => 'required|string|max:20|regex:/^[A-Z]{3}-[0-9]{3}$/|unique:vehiculos,placa,' . $vehiculo->id_vehiculo . ',id_vehiculo',
            'marca'  => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'anio'   => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'color'  => 'nullable|string|max:50',
            'tipo'   => 'nullable|string|max:50',
            'vin'    => 'nullable|string|max:50',
        ], [
            'placa.required' => 'Debe completar todos los campos obligatorios.',
            'placa.unique'   => 'La placa ingresada ya está registrada en el sistema.',
            'placa.regex'    => 'El formato de la placa no es válido. Use el formato: ABC-123.',
            'marca.required' => 'Debe completar todos los campos obligatorios.',
            'modelo.required'=> 'Debe completar todos los campos obligatorios.',
        ]);

        $vehiculo->update([
            'placa'  => strtoupper($request->placa),
            'marca'  => $request->marca,
            'modelo' => $request->modelo,
            'anio'   => $request->anio,
            'color'  => $request->color,
            'tipo'   => $request->tipo,
            'vin'    => $request->vin,
        ]);

        return redirect()->route('cliente.vehiculos.show', $vehiculo)
            ->with('success', 'Vehículo actualizado exitosamente.');
    }
}
