<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::paginate(15);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'              => 'required|string|max:150',
            'precio_base'         => 'required|numeric|min:0',
            'duracion_estimada'   => 'nullable|numeric|min:0',
        ], [
            'nombre.required'      => 'Por favor complete todos los campos obligatorios.',
            'precio_base.required' => 'Por favor complete todos los campos obligatorios.',
        ]);

        Servicio::create($request->only(['nombre', 'descripcion', 'precio_base', 'duracion_estimada']) + ['activo' => true]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio registrado exitosamente.');
    }

    public function show(Servicio $servicio)
    {
        return view('admin.servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre'              => 'required|string|max:150',
            'precio_base'         => 'required|numeric|min:0',
            'duracion_estimada'   => 'nullable|numeric|min:0',
        ]);

        $servicio->update($request->only(['nombre', 'descripcion', 'precio_base', 'duracion_estimada', 'activo']));

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->update(['activo' => false]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio desactivado exitosamente.');
    }
}
