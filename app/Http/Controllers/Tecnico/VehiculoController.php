<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Tecnico\EstadoVehiculo;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehiculo::with(['cliente.usuario', 'estado']);

        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }

        $vehiculos = $query->paginate(15);
        $estados   = EstadoVehiculo::all();

        return view('tecnico.vehiculos.index', compact('vehiculos', 'estados'));
    }

    public function show(Vehiculo $vehiculo)
    {
        $vehiculo->load(['cliente.usuario', 'estado', 'fotos', 'historial', 'ordenesTrabajo.estado']);
        return view('tecnico.vehiculos.show', compact('vehiculo'));
    }

    // TDLP-008: Actualizar estado del vehículo
    public function actualizarEstado(Request $request, Vehiculo $vehiculo)
    {
        $estadosPermitidos = ['Ingresado', 'En espera', 'En reparación', 'Finalizado', 'Entregado'];

        $request->validate([
            'id_estado' => 'required|exists:estado_vehiculo,id_estado',
        ]);

        $nuevoEstado = EstadoVehiculo::findOrFail($request->id_estado);

        if (!in_array($nuevoEstado->nombre_estado, $estadosPermitidos)) {
            return back()->with('error', 'El estado seleccionado no es válido. Los estados permitidos son: ' . implode(', ', $estadosPermitidos) . '.');
        }

        // TDLP-008 escenario 5: sin orden activa
        $tieneOrdenActiva = $vehiculo->ordenesTrabajo()
            ->whereHas('estado', fn($q) => $q->whereNotIn('nombre', ['Finalizado', 'Entregado']))
            ->exists();

        if (!$tieneOrdenActiva) {
            return back()->with('error', 'No es posible actualizar el estado porque el vehículo no tiene una orden de servicio activa.');
        }

        $vehiculo->update(['id_estado' => $request->id_estado]);

        return back()->with('success', 'Estado del vehículo actualizado correctamente.');
    }

    // TDLP-009: Subir foto del vehículo
    public function subirFoto(Request $request, Vehiculo $vehiculo)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'foto.required' => 'Seleccione una fotografía.',
            'foto.mimes'    => 'El formato del archivo no es válido. Se aceptan únicamente: JPG, PNG, JPEG.',
            'foto.max'      => 'El archivo supera el tamaño máximo permitido de 10 MB.',
        ]);

        $path = $request->file('foto')->store('vehiculos', 'public');

        $vehiculo->fotos()->create([
            'ruta_foto'   => $path,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Fotografía subida exitosamente.');
    }
}
